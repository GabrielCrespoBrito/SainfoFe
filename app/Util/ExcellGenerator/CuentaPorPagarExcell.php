<?php

namespace App\Util\ExcellGenerator;

use Illuminate\Support\Facades\Log;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class CuentaPorPagarExcell extends ExcellGenerator
{
  const HEADER = [
    "Fecha Cpa" , "Fecha Cpa.", 'RUC', "Razòn Social", "Nº Oper", "T.D", "Nº Doc", "Mon", "Total", "Pagado", "Saldo"
  ];

  public $nombreEmpresa;
  public $linea = 1;

  public $periodo = "";
  public $title_hoja = "";
  
  const TITLE_HOJA = "REPORTE FACTURACIÓN ELECTRONICA";
  
  public function __construct($data , $title_hoja, $nombreEmpresa  )
  {
    parent::__construct($data, true, null);

    $this->nombreEmpresa = $nombreEmpresa;
    $this->title_hoja = $title_hoja;
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
    return $this->title_hoja;
  }

  public function getFiltersNames()
  {
    return [
      'Empresa',
      'Reporte',
      'Fecha Reporte',
    ];
  }


  public function getFiltersValues()
  {
    return [
      $this->nombreEmpresa,
      $this->title_hoja,
      datePeru('Y-m-d H:m:s')
    ];
  }

  /**
   * Cabecera con nombre del periodo, periodo y moneda. Tambien cabecera de la tabla
   *
   * @param [type] $sheet
   * @return void
   */
  public function setTotal( &$sheet )
  {

  }


  public function headerSheet(&$sheet)
  {
    // Cabecera con nombre del periodo, periodo y moneda
    $sheet->row(1 , $this->getFiltersNames());
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
    $columnaNombre = $isTotalGeneral ? "TOTAL GENERAL" : "TOTAL CLIENTE";

    $lineaData = [
      // $columnaNombre, '', "TOTAL S./ ",  fixedValue($total['01']), '',  'TOTAL S./',  fixedValue($total['01']) , '', '', '', ''
      $columnaNombre, '', "TOTAL S./ ",  fixedValue($total['01']) , '',  'TOTAL USD./',  fixedValue($total['02']) , '', '', '', ''
    ];

    $sheet->row($this->linea, $lineaData);
    $sheet->row($this->linea, function ($row) {
      $row->setBackground('#000000');
      $row->setFontColor('#FFFFFF');
    });

    $this->sumLinea();
    $this->sumLinea();
  }


  public function rowHeaderCliente(  $clienteText, &$sheet )
  {
    $sheet->row($this->linea, [ $clienteText ]);
    $sheet->row($this->linea, function ($row) {
      $row->setBackground('#CCCCCC');
      $row->setFontColor('#000000');
    });

    $this->sumLinea();
  }

  public function processVentas( $docs, &$sheet )
  {
    foreach(  $docs as $venta ){
      $sheet->row($this->linea,  $venta );
      $this->sumLinea();
    }
  }

  public function processGrupoCliente(&$sheet)
  {
    $clientes = $this->data['data']['docs'];

    foreach( $clientes as $clienteData ){
      $this->rowHeaderCliente( $clienteData['info'] , $sheet );
      $this->processVentas( $clienteData['docs'] , $sheet );
      $this->rowTotal( $clienteData['total'], false, $sheet );
    }
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->headerSheet($sheet);

      if( $this->data['agrupacion']  == "cliente" ){
        $this->processGrupoCliente(  $sheet );
      }

      else {
        $this->processVentas( $this->data['data']['docs'] , $sheet );
      }

      // Columna del total del tipo general
      $this->rowTotal( $this->data['data']['total'] , true, $sheet);
      
    });

  }
}
