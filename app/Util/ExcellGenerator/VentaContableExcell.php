<?php

namespace App\Util\ExcellGenerator;

use App\ModuloMonitoreo\StatusCode\StatusCode;

class VentaContableExcell extends ExcellGenerator
{
  const HEADER = [
    "Fecha Emis" , "Fecha Vcto.", 'Tipo Doc.', "Serie", "Numero", "TE", "Ruc", "Razon Social", "Base Imponible", "Exo", "Inaf", "ISC", "IGV", 'ICBPER', "Total Soles", "T.C", "Fecha Doc. Mod", "Tipo Doc. Mod", "Serie. Mod" , "Numero. Mod", "Total USD ", "Estado Sunat"
  ];

  public $nombreEmpresa;
  public $linea = 1;

  public $periodo = "";

  const TITLE_HOJA = "REPORTE FACTURACIÓN ELECTRONICA";
  
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
      'REPORTE DE VENTAS',
      $this->periodo,
      "SOLES",
      datePeru('Y-m-d H:m:s')
    ];
  }

  /**
   * Cabecera con nombre del periodo, periodo y moneda. Tambien cabecera de la tabla
   *
   * @param [type] $sheet
   * @return void
   */
  public function setTotal( &$sheet ){

  }


  public function headerSheet(&$sheet)
  {
    // Cabecera con nombre del periodo, periodo y moneda
    $sheet->row(1 , $this->getFiltersNames());
    $sheet->row(1, function ($row) {
      $row->setBackground( '#000000' );
      $row->setFontColor( '#FFFFFF' );
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
  public function rowTipoDocumentoHeader($info_tipo, &$sheet)
  {
    $lineaData = [sprintf('%s %s', $info_tipo['info']['codigo'], $info_tipo['info']['nombre'] ) ];
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
  public function rowDocumento( $venta,  &$sheet)
  {
    $estado = $venta['info']['serie'] . '-' . $venta['info']['numero'] . ' | ' . StatusCode::getNombreByCode($venta['info']['estado']);
    $lineaData = [
      $venta['info']['fecha_emision'],
      $venta['info']['fecha_vencimiento'],
      $venta['info']['tipo_documento'],
      $venta['info']['serie'],
      $venta['info']['numero'],
      $venta['info']['cliente_tipo_documento'],
      $venta['info']['cliente_documento'],
      $venta['info']['cliente_nombre'],
      $venta['total']['base_imponible'],
      $venta['total']['exonerada'],
      $venta['total']['inafecta'],
      $venta['total']['isc'],
      $venta['total']['igv'],
      $venta['total']['icbper'],
      $venta['total']['importe_soles'],
      $venta['total']['tc'],
      $venta['info']['docref_fecha_emision'],
      $venta['info']['docref_tipo_documento'],
      $venta['info']['docref_serie'],
      $venta['info']['docref_numero'],
      $venta['total']['importe_dolares'],
      $estado
    ];

    $sheet->row($this->linea, $lineaData);
    $this->sumLinea();
  }

  public function rowTotal( $total , $isTotalGeneral = true,  &$sheet)
  {
    $columnaNombre = $isTotalGeneral ? "TOTAL GENERAL" : "TOTAL";

    $lineaData = [
      $columnaNombre, '', '', '', '', '', '', '',
      fixedValue($total['base_imponible']),
      fixedValue($total['exonerada']),
      fixedValue($total['inafecta']),
      fixedValue($total['isc']),
      fixedValue($total['igv']),
      fixedValue($total['icbper']),
      fixedValue($total['importe_soles']),
      '', '', '', '', '',
      fixedValue($total['importe_dolares']),
      ''
    
    ];

    $sheet->row($this->linea, $lineaData);
    $sheet->row($this->linea, function ($row) {
      $row->setBackground('#CCCCCC');
      $row->setFontColor('#000000');
    });

    $this->sumLinea();
  }



  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->headerSheet($sheet);

      $ventas_group_by_tipo = $this->data['items'];

      // Iterar tipos de documentos
      foreach( $ventas_group_by_tipo as $info_tipo ) {
        
        $this->rowTipoDocumentoHeader( $info_tipo , $sheet );

        // Iterar documentos
        foreach ($info_tipo['items'] as $doc) {
          // Columna de documento y sumar a totales
          $this->rowDocumento($doc,$sheet);
        }

        // Columna del total del tipo de documento
        $this->rowTotal( $info_tipo['total'] , false, $sheet);
      }

      // Columna del total del tipo general
      $this->rowTotal(  $this->data['total'],  true, $sheet);
      
    });

  }
}
