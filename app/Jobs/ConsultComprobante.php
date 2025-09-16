<?php

namespace App\Jobs;

use App\Empresa;
use App\Cotizacion;
use App\PDFPlantilla;
use App\TipoDocumentoPago;
use App\Rules\RucValidation;
use App\Util\PDFGenerator\PDFGenerator;
use App\VentaCompartida;

class ConsultComprobante
{
  public $success = true;
  public $error;
  public $path;
  public $data;
  public $token;
  public $documento;
  
  public function __construct( $token, $documento )
  {
    $this->token = $token;
    $this->documento = $documento;
  }

  public function setError($error)
  {
    $this->error = $error;
    return $this->success = false;
  }

  public function setSuccess($path)
  {
    $this->path = $path;
    return $this->success = true;
  }  

  public function validaToken()
  {
    $token_app = config('app.token_cc');
    if ($token_app != $this->token) {
      return $this->setError("Token Error");
    }
  }

  public function validateDocumento()
  {
    $parts = explode('-', $this->documento);

    if (count($parts) != 4) {
      return $this->setError("Error en el Número del documento");
    }

    list( $ruc, $tidcodi, $serie, $numero ) = $parts;

    if (!(new RucValidation)->validate($ruc)) {
      return  $this->setError("Ruc Incorrecto");
    }

    if (! (
      $tidcodi == TipoDocumentoPago::FACTURA ||
      $tidcodi == TipoDocumentoPago::BOLETA ||
      $tidcodi == TipoDocumentoPago::NOTA_CREDITO ||
      $tidcodi == TipoDocumentoPago::NOTA_DEBITO ||
      $tidcodi == TipoDocumentoPago::PROFORMA ||
      $tidcodi == TipoDocumentoPago::PREVENTA||
      $tidcodi == TipoDocumentoPago::NOTA_VENTA ||
      $tidcodi == TipoDocumentoPago::ORDEN_PAGO ||
      $tidcodi == TipoDocumentoPago::ORDEN_COMPRA
    )) {
      return $this->setError("Tipo de Documento Incorrecto");
    }

    if (!is_numeric($numero)) {
      return $this->setError("Numero de Documento Incorrecto");
    }

    if($tidcodi == TipoDocumentoPago::FACTURA ||
      $tidcodi == TipoDocumentoPago::BOLETA ||
      $tidcodi == TipoDocumentoPago::NOTA_CREDITO ||
      $tidcodi == TipoDocumentoPago::NOTA_DEBITO ){

        if(VentaCompartida::isCompartido($ruc, $tidcodi, $serie, $numero) == false){
        return $this->setError("No Existe el Documento");

        }
      }

    $this->data = (object) [
      'ruc' => $ruc,
      'tidcodi' => $tidcodi,
      'serie' => $serie,
      'numero' => $numero,
    ];

    return true;
  }

  public function validate()
  {
    if(  $this->validaToken()){
      return false;
    }

    return $this->validateDocumento();
  }


  public function getDocumento()
  {
    $data = $this->data;
    
    $empresa = Empresa::findByRuc($data->ruc);
    $codigo = optional($empresa)->codigo;
    $fh = fileHelper($data->ruc, $codigo);
    $documentName = sprintf('%s-%s-%s-%s.pdf', $data->ruc, $data->tidcodi, $data->serie, agregar_ceros($data->numero, 6, 0));
    
    // dd($documentName);

    try {
      $pdf = $fh->getPdf($documentName);
      // logger()->info('pdf', $pdf);
      $path = getTempPath($documentName, $pdf);
      return $this->setSuccess($path);
    } catch (\Throwable $th) {
      logger()->error('@ERROR No se encontro el documento ' . $documentName . ' ' . $th->getMessage());
      return $this->setError("No se encontro el documento " . $documentName);
    }
  }

  public function createDocumento()
  {
    $data = $this->data;

    // Si el Documento que no es proforma, preventa u orden_de_pago. No hay nada que hacer
    if((
      $data->tidcodi   == TipoDocumentoPago::PROFORMA ||
      $data->tidcodi   == TipoDocumentoPago::PREVENTA ||
      $data->tidcodi   == TipoDocumentoPago::ORDEN_PAGO) == false
    ) {
      return;
    }

    // Si la Empresa no esta esta en la BD
    $empresa = Empresa::findByRuc($data->ruc);
    if( $empresa == null ){
      return;
    }

    empresa_bd_tenant($empresa->id());

    $id = $data->tidcodi . '-' . $data->serie . '-' . $data->numero;
    $cotizacion = Cotizacion::findByUni($id);

    if( ! $cotizacion ){
      return;
    }

    // $cotizacion->generatePDF
    try {
      $path = $cotizacion->generatePDF(
        PDFPlantilla::FORMATO_A4,
        PDFGenerator::HTMLGENERATOR,
        $empresa->hasImpresionIGV(),
        true,
        true
      );

      $this->setSuccess($path);
    } catch (\Throwable $th) {
      return $this->setError($th->getMessage());
    }
  }

  public function getOrCreateDocumento()
  {
    if( ! $this->getDocumento()){
      $this->createDocumento();
    }
  }

  public function handle()
  {
    if( $data = $this->validate() === false ){
      return;
    }

    $this->getOrCreateDocumento($data);
  }
}
