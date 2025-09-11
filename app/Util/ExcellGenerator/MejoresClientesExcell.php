<?php

namespace App\Util\ExcellGenerator;

class MejoresClientesExcell extends ExcellGenerator
{
  use LineTrait;
    
  protected $sheet;

  const HEADER = [
    "#",
    "CODIGO ",
    "RUC ",
    "RAZON SOCIAL ",
    "CANTIDAD VENTAS ",
    "IMPORTE ",
  ];

  public $linea = 1;
  public $title_hoja = "";
  public $fecha_inicio = "";
  public $fecha_final = "";

  const TITLE_HOJA = "reporte_vendedor_ventas_productos";

  public function __construct($data, $title_hoja = "hoja_excell")
  {
    parent::__construct($data, true, null);
    $this->title_hoja = $title_hoja;
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
    $this->sheet->row( $lineTitulo =  $this->getLineaAndSum(),  [ 'REPORTE DE MEJORES CLIENTES']);
    $this->sheet->row( $this->getLineaAndSum(),  [$this->data['empresa_nombre'] ]);
    $this->sheet->row( $this->getLineaAndSum(),  [ "'". $this->data['empresa_ruc']]);
    $this->sheet->row( $this->getLineaAndSum(),  [ 'Fecha Desde' , $this->data['fecha_desde']]);
    $this->sheet->row( $this->getLineaAndSum(),  [ 'Fecha Hasta' , $this->data['fecha_hasta']]);
    $this->sheet->row( $this->getLineaAndSum(2),  [ 'Local: ' ,  $this->data['local']]);

    $this->sheet->row($lineTitulo, function ($row) {$row->setFontWeight('bold');});


    $this->sheet->row($this->getLineaAndSum(), $this->getHeader());
    $this->sheet->row($this->getLastLine(), function ($row) {
      $row->setFontWeight('bold');
    });

  }

  public function process()
  {

    $items = $this->data['clientes'];



    $loop = 1;
    foreach ($items as $cliente) {

        $this->sheet->row($this->getLineaAndSum(), [
        $loop++,
        $cliente['codigo'], 
        $cliente['documento'], 
        $cliente['nombre'], 
        $cliente['cantidad'], 
        $cliente['importe']
        ]);
    }
  }
  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->sheet = $sheet;
      $this->sheet->setAutoSize(false);
      $this->sheet->setFontFamily('Arial');
      $this->sheet->setFontSize(10);

      $this->headerSheet();
      $this->process();
    });
  }
}
