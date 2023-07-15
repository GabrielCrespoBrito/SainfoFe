<?php

namespace App\Util\ExcellGenerator;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Moneda;
use App\TipoDocumentoPago;

class CompraMensualContableExcell extends ExcellGenerator
{
  const HEADER = [
    "Fecha Emis" , "Fecha Vcto.", 'Tipo Doc.', "Serie", "Numero", "TE", "Ruc", "Razon Social", "Base Imponible", "Exo", "Inaf", "ISC", "IGV", 'ICBPER', "Total Soles", "T.C", "Fecha Doc. Mod", "Tipo Doc. Mod", "Serie. Mod" , "Numero. Mod", "Total USD "
  ];

  public $nombreEmpresa;
  public $linea = 1;
  public $periodo = "";

  const TITLE_HOJA = "REPORTE MENSUAL DE COMPRAS";
  
  public function __construct($data, $periodo, $nombreEmpresa)
  {
    parent::__construct($data, true, null);

    $this->periodo = $periodo;
    $this->nombreEmpresa = $nombreEmpresa;    
  }


  public function getLinea()
  {
    $this->linea;
  }

  public function setLinea( int $linea)
  {
    $this->linea = $linea;
  }

  public function sumLinea()
  {
    $this->linea += 1;
  }

  public function getHeader()
  {
    return self::HEADER;
  }

  public function getSheetTitle()
  {
    return self::TITLE_HOJA;
  }

  public function getFiltersNames()
  {
    return [
      'Empresa',
      'Reporte',
      'Periodo',
      'Moneda',
      'Fecha Reporte',
    ];
  }


  public function getFiltersValues()
  {
    return [
      $this->nombreEmpresa ,
      'REPORTE MENSUAL DE COMPRAS',
      $this->periodo,
      "SOLES",
      datePeru('Y-m-d H:i:s')
    ];
  }

  /**
   * Cabecera con nombre del periodo, periodo y moneda. Tambien cabecera de la tabla
   *
   * @param [type] $sheet
   * @return void
   */
  public function headerSheet(&$sheet)
  {
    $sheet->row(1, $this->getFiltersNames());
    $sheet->row(1, function ($row) {
      $row->setBackground('#000000');
      $row->setFontColor('#FFFFFF');
    });
    
    $sheet->row(2, $this->getFiltersValues());

    $sheet->row(4, $this->getHeader());
    $sheet->row(4, function ($row) {
      $row->setBackground('#000000');
      $row->setFontColor('#FFFFFF');
    });

    $this->linea = 5;
  }


  /**
   * Columna para mostrar el nombre del tipo de documento tipo de documento
   *
   * @return bool
   */
  public function rowTipoDocumentoHeader($tidCodi, &$sheet)
  {
    // Poner fila separadora del tipo de documento
    $nombreTipoDocumento = $tidCodi . ' ' .  nombreDocumento($tidCodi);
    $lineaData = [$nombreTipoDocumento];
    $sheet->row($this->linea, $lineaData);
    $sheet->row($this->linea, function ($row) {
      $row->setBackground('#CCCCCC');
      $row->setFontColor('#000000');
    });

    $this->sumLinea();
  }



  /**
   * Columna de un documento
   *
   * @return bool
   */
  public function rowDocumento( $doc,  &$sheet)
  {
    $sheet->row($this->linea, [
      $doc['info']['fecha'],
      $doc['info']['fecha_vencimiento'],
      $doc['info']['tipo_documento'],
      $doc['info']['serie'],
      $doc['info']['numero'],
      $doc['info']['tipo_documento_cliente'],
      $doc['info']['documento_cliente'],
      $doc['info']['nombre_cliente'],
      $doc['total']['base_imponible'],
      $doc['total']['exonerada'],
      $doc['total']['inafecta'],
      $doc['total']['isc'],
      $doc['total']['igv'],
      $doc['total']['icbper'],
      $doc['total']['importe_soles'],
      $doc['info']['tipo_cambio'],
      $doc['info']['fecha_documento_referencia'],
      $doc['info']['tipo_documento_referencia'],
      $doc['info']['serie_documento_referencia'],
      $doc['info']['numero_documento_referencia'],
      $doc['total']['importe_dolares'],
    ]);
    $this->sumLinea();
  }

  public function rowTotal( $isTotalGeneral = true, &$sheet, $total , $nombreTipoDocumento = null )
  {
    $columnaNombre = $isTotalGeneral ?  "TOTAL REPORTE" : "TOTAL $nombreTipoDocumento ";
    $sheet->row($this->linea, [
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        $columnaNombre,
        $total['base_imponible'],
        $total['exonerada'],
        $total['inafecta'],
        $total['isc'],
        $total['igv'],
        $total['icbper'],
        $total['importe_soles'],
        '',
        '',
        '',
        '',
        '',
        $total['importe_dolares'],
    ]
    );


    $sheet->row($this->linea, function ($row) {
      $row
      ->setBackground('#CCCCCC')
      ->setFontWeight()
      ->setFontColor('#000000');
    });


    // Si es el total del tipo de documento agregar dos lineas de separacion adicional
    if( ! $isTotalGeneral ){
      $this->sumLinea();
      $this->sumLinea();
    }
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {
            
      $this->headerSheet($sheet);

      // Iterar tipos de documentos
      foreach( $this->data['items'] as $tidCodi => $docs ) {        
        $this->rowTipoDocumentoHeader( $tidCodi , $sheet );
        // Iterar documentos
        foreach ( array_chunk($docs['items'],100)  as $docs_) {
          foreach($docs_ as $doc ){
            // Columna de documento y sumar a totales
            $this->rowDocumento($doc,$sheet);
          }
        }
        // Columna del total del tipo de documento
        $this->rowTotal( false, $sheet , $docs['total'] , $docs['info']['nombre'] );
      }

      // Columna del total del tipo general
      $this->rowTotal(true,$sheet, $this->data['total'] );
    });

  }
}
