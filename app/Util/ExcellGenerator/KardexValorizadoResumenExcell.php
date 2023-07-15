<?php

namespace App\Util\ExcellGenerator;

class KardexValorizadoResumenExcell extends ExcellGenerator
{
  use LineTrait;
  protected $sheet;

  const HEADER = [
    "CÓDIGO DE LA EXISTENCIA",
    "TIPO DE EXIST. TABLA 5",
    "DESCRIPCIÓN",
    "CÓDIGO DE LA UNIDAD DE MEDIDA(TABLA 6)",
    "SALDO INICIAL",
    "INGRESO",
    "SALIDA",
    "SALDO CAN",
    "COSTO UNITARIO",
    "COSTO TOTAL"
  ];


  public $nombreEmpresa;
  public $linea = 1;

  public $periodo = "";
  public $title_hoja = "";
  public $fecha_inicio = "";
  public $fecha_final = "";

  const TITLE_HOJA = "REPORTE FACTURACIÓN ELECTRONICA";

  public function __construct($data, $title_hoja = "hoja_excell")
  {
    parent::__construct($data, true, null);

    // $this->nombreEmpresa = $nombreEmpresa;
    $this->title_hoja = $title_hoja;
    // $this->fecha_inicio = $fecha_inicio;
    // $this->fecha_final = $fecha_final;
  }


  public function getHeader()
  {
    return self::HEADER;
  }

  public function getSheetTitle()
  {
    return $this->title_hoja;
  }

  public function headerSheet()
  {

    $this->sheet->row($this->getLineaAndSum(), ['FORMATO 3.7: "LIBRO DE INVENTARIOS Y BALANCES - DETALLE DEL SALDO DE"']);
    $this->sheet->row($this->getLineaAndSum(), ['LA CUENTA 20 - MERCADERIAS Y LA CUENTA 24 - MATERIAS PRIMAS']);
    $this->sheet->row($this->getLineaAndSum(), ['EJERCICO: ' . $this->data['periodo']]);
    $this->sheet->row($this->getLineaAndSum(), ['RUC: ' . $this->data['ruc']]);
    $this->sheet->row($this->getLineaAndSum(), ['DENOMIRACIÓN: ' . $this->data['nombre']]);
    $this->sheet->row($this->getLineaAndSum(2), ['METODO DE EVALUACIÓN APLICADO PROMEDIO']);


    $this->sheet->row($this->getLineaAndSum(), $this->getHeader());
    $this->sheet->row($this->getLastLine(), function ($row) {
      $row->setFontWeight('bold');
    });
  }


  public function processProductos()
  {
    $items = $this->data['data'];

    $total = 0;
    foreach ($items as $producto) {

      $this->sheet->row($this->getLineaAndSum(),  [
        $producto['code'],
        "05",
        $producto['descripcion'],
        "07",
        $producto['stock_inicial']['quantity'],
        $producto['cant_total_ingreso'],
        $producto['cant_total_salida'],
        $producto['stock_final']['quantity'],
        $producto['stock_final']['cost_unit'],
        $producto['stock_final']['total']
      ]);

      $total += $producto['stock_final']['total'];
    }

    $this->sheet->row($this->getLineaAndSum(),  [
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      $total
    ]);

    // Total 
    $this->sheet->row($this->getLastLine(), function ($row) {
      $row->setFontWeight('bold');
    });
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->sheet = $sheet;
      $this->sheet->setAutoSize(false);
      $this->sheet->setFontFamily('Arial');
      $this->sheet->setFontSize(10);

      $this->headerSheet();
      $this->processProductos();
    });
  }
}
