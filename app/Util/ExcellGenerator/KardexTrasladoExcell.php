<?php

namespace App\Util\ExcellGenerator;

class KardexTrasladoExcell extends ExcellGenerator
{

  const HEADER = [
    "FECHA", "AÑO", 'CODIGO', "DESCRIPCIÒN", "PESO",
    "UNIDAD MEDIDA", "ALMACEN", "STOCK INICIAL", "INGRESO", "SALIDA", "STOCK ACTUAL", "COSTO", "NOMBRE PROVEEDOR"
  ];

  public $nombreEmpresa;
  public $linea = 1;

  public $periodo = "";
  public $title_hoja = "";
  public $fecha_inicio = "";
  public $fecha_final = "";

  const TITLE_HOJA = "REPORTE FACTURACIÓN ELECTRONICA";

  public function __construct($data, $title_hoja, $nombreEmpresa, $fecha_inicio, $fecha_final)
  {
    parent::__construct($data, true, null);

    $this->nombreEmpresa = $nombreEmpresa;
    $this->title_hoja = $title_hoja;
    $this->fecha_inicio = $fecha_inicio;
    $this->fecha_final = $fecha_final;
  }

  public function getLinea()
  {
    $this->linea;
  }

  public function setLinea(int $linea)
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
      'Fecha Impresion',
    ];
  }

  public function getFiltersValues()
  {
    return [
      $this->nombreEmpresa,
      $this->title_hoja,
      $this->fecha_inicio . ' - ' . $this->fecha_final,
      datePeru('Y-m-d H:m:s')
    ];
  }

  public function headerSheet(&$sheet)
  {
    // Cabecera con nombre del periodo, periodo y moneda
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


  public function processProductos(&$sheet)
  {
    $items = $this->data;


    foreach ($items as $item) {
      $sheet->row($this->linea,  $item);
      $this->sumLinea();
    }
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {
      $this->headerSheet($sheet);
      // $this->processProductos($sheet);
    });
  }
}
