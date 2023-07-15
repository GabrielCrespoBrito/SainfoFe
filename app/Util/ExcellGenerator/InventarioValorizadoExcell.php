<?php

namespace App\Util\ExcellGenerator;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Moneda;

class InventarioValorizadoExcell extends ExcellGenerator
{
  const TITLE_HOJA = "INVENTARIO VALORIZADO";

  const HEADER = [
    'Codigo',
    'Unidad',
    'Articulo',
    'Marca',
    'Peso',
    'Costo',
    'Stock',
    'Importe',
  ];

  public $nombreEmpresa;
  public $local_nombre;
  public $moneda_nombre;
  public $linea = 1;


  public function __construct($data_report)
  {
    parent::__construct($data_report, true, null);
    $this->nombreEmpresa = $data_report['nombre_empresa'];
    $this->local_nombre = $data_report['local_nombre'];
    $this->moneda_nombre = $data_report['moneda_nombre'];
    $this->tipo_existencia_nombre = $data_report['tipo_existencia_nombre'];
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
      'Moneda',
      'Local',
      'Tipo Existencia',
      'Fecha Reporte',
    ];
  }


  public function getFiltersValues()
  {
    return [
      $this->nombreEmpresa,
      'REPORTE DE INVENTARIO VALORIZADO',
      $this->moneda_nombre,
      $this->local_nombre,
      $this->tipo_existencia_nombre,
      datePeru('Y-m-d H:m:s')
    ];
  }


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

  public function rowHeaderGrupo( $grupo , &$sheet)
  {
    $lineaData = [
      $grupo['info_group']['codigo'] . ' ' . $grupo['info_group']['nombre'],
      '', '', '', '', '', '', '', 
    ];
    
    $sheet->row($this->linea, $lineaData);

    $sheet->row($this->linea, function ($row) {
      $row->setBackground('#B3B3B385');
      $row->setFontColor('#ffffff');
    });

    $this->sumLinea();
  }

  public function rowTotalGrupo( $grupo , &$sheet)
  {
    $lineaData = [
      '', '', '', '', '', '', 'TOTAL GRUPO',
     $grupo['info_group']['total'],
    ];
    
    $sheet->row($this->linea, $lineaData);
    $sheet->row($this->linea, function ($row) {
      $row->setBackground('#B3B3B385');
      $row->setFontColor('#ffffff');
    });

    $this->sumLinea();
    $this->sumLinea();
  }


  public function rowTotalGeneral( $total_general , &$sheet)
  {
    $lineaData = [
      '', '', '', '', '', '', 'TOTAL GENERAL',
     $total_general,
    ];
    
    $sheet->row($this->linea, $lineaData);
    $sheet->row($this->linea, function ($row) {
      $row->setBackground('#B3B3B385');
      $row->setFontColor('#ffffff');
    });

  }



  public function rowProducto( $producto , &$sheet)
  {
    $lineaData = [
      $producto['id'],
      $producto['unidad'],
      $producto['nombre'],
      $producto['marca'],
      $producto['peso'],
      $producto['costo'],
      $producto['stock'],
      $producto['importe'],
    ];
    
    $sheet->row($this->linea, $lineaData);
    $this->sumLinea();
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->headerSheet($sheet);
      $total_general = $this->data['total_general'];
      $grupos_chunk = array_chunk($this->data['data'], 5);
      // Iterar Los grupos
      foreach ( $grupos_chunk as $grupos ) {
        foreach( $grupos as $grupo ){
          $this->rowHeaderGrupo($grupo, $sheet);
          foreach( $grupo['products_group'] as $producto ){
            $this->rowProducto($producto, $sheet);
          }
          $this->rowTotalGrupo($grupo, $sheet);
        }
      }
      $this->rowTotalGeneral( $total_general, $sheet );

    });
  }
}
