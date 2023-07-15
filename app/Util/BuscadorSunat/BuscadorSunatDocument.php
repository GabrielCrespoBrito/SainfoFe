<?php

namespace App\Util\BuscadorSunat;

use App\ModuloMonitoreo\Empresa\Empresa;
use App\ModuloMonitoreo\DocSerie\DocSerie;
use App\ModuloMonitoreo\Document\Document;
use App\Util\Sunat\Request\credentials\CredentialManual;
use App\Util\Sunat\Services\SunatConsultDocumentOficinalProduction;

/**
 * Clase para buscar un documento especifico
 * 
 */
class BuscadorSunatDocument
{
  public $sunatConsult;
  public $docSerie;
  public $numero_inicio;
  public $numero_final;

  /**
   * Si se va a buscar nuevamnete documentos que ya han sido consultados
   * 
   * @var bool
   */
  public $reprocesar;


  /**
   * Mes del documento
   * 
   * @var bool
   */
  public $mescodi;

  /**
   * Reporte de los documentos consultados
   * 
   */
  public $reporte = [];

  /**
   * Documento actual con el que se esta trabajando
   * 
   * @var Document
   */
  public $document;

  public function __construct(Empresa $empresa, DocSerie $docSerie, int $numero_inicio, int $numero_final, $reprocesar = true, $mescodi)
  {
    set_time_limit(600);
    $this->codes = cacheHelper('docstatus.all');
    $this->empresa = $empresa;
    $this->docSerie = $docSerie;
    $this->reprocesar = $reprocesar;
    $this->mescodi = $mescodi;

    $this->tipo_documento = $docSerie->tipo_documento;
    $this->serie_codigo = $docSerie->serie;
    $this->descripcionSerie = $docSerie->descripcionFull();
    
    $this->sunatConsult = $empresa->getCommunicator();

    $this->numero_inicio = $numero_inicio;
    $this->numero_final = $numero_final;
  }






  public function procesar()
  {
    for ($i = $this->numero_inicio; $i <= $this->numero_final; $i++) {
      $this->establishDocument($i);
      $this->processDocument();
    }
  }


  public function getParamsDocument()
  {
    return [[
      'rucComprobante' => $this->empresa->ruc,
      'tipoComprobante' => $this->tipo_documento,
      'serieComprobante' => $this->serie_codigo,
      'numeroComprobante' => $this->getDocumento()->numero
    ]];

  }

  public function processDocument()
  {
    $code = '-';
    $descripcion = 'No se pudo realizar';

    if ($this->reprocesar || $this->document->buscadoEnSunat() == false) {
      $parameters = [
        'ruc' => $this->empresa->ruc,
        'tipo_documento' => $this->tipo_documento,
        'serie' => $this->serie_codigo,
        'numero' => $this->getDocumento()->numero,
      ];

      $response = $this->sunatConsult
      ->setParams( $this->getParamsDocument() )
      ->communicate()
      ->getResponse();

      $data = $this->saveDocumentResponse($response);

      $code = $data['code'];
      $descripcion = $data['descripcion'];
    }

    else {
      $status = $this->getDocumento()->status->status;
      if( $status->exists){
        $code = $status->status_code;
        $descripcion = $status->status_message;
      }  
    
    }

    $this->addtoReport( $code, $descripcion );
  }


  public function establishDocument($numero)
  {
    $document = $this->docSerie->documents->where('numero', $numero)->first();

    if( $document ) {
      if ( is_null($document->mescodi)) {
        $document->update(['mescodi' => $this->mescodi]);
      }
    }

    if ($document == null) {
      $document = $this->docSerie->documents()->create([
        'numero' => $numero,
        'mescodi' => $this->mescodi,
        ]);
    }

    return $this->setDocumento($document);
  }

  public function setDocumento($document)
  {
    $this->document = $document;
  }


  public function getDocumento()
  {
    return $this->document;
  }


  public function addtoReport( $code , $descripcion  )
  {
    $data = [
      'descripcionSerie' => $this->descripcionSerie,
      'numero' => $this->getDocumento()->numero,
      'codigo' => $code,
      'descripcion' => $descripcion
    ];

    array_push($this->reporte, $data);
  }

  /**
   * Guardar la respuesta del documento
   * 
   * @return void
   */
  public function saveDocumentResponse($response)
  {
    if ($response['client_connection_success'] && $response['communicate']) {
      $status = $response['commnucate_data']->status;
      $this->getDocumento()->updateEstadoBusqueda(true);
      $this->getDocumento()->registerStatus($status->statusCode, $status->statusMessage);
      $code = $status->statusCode;
      $descripcion = $status->statusMessage;
    }
    else {
      if( $response['client_connection_success'] == false ) {
        $code = "9999";
        $descripcion = $response['client_connection_response']['error'];
      }
      else if ( $response['communicate'] == false) {
        $code = "9999";
        $descripcion = $response['communicate_response']['communicate_data']->getMessage();
      }
    }


    return [
      'code'  => $code,
      'descripcion'  => $descripcion,
    ];
  }

  public function getReporte()
  {
    return collect($this->reporte);
  }
}