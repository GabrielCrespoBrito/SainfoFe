<?php

namespace App\Util\ExcellGenerator;

class CotizacionExcell extends ExcellGenerator
{
  use LineTrait;
    
  protected $sheet;

  const HEADER = [
    "Nº  ",
    "ESTADO  ",
    "FECHA ",
    "DOC ",
    "NOMBRE ",
    "USUARIO ",
    "OBSERVACION ",
    "ZONA ",
    "TOTAL ",
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
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['info']['reporte_nombre']]);
    $this->sheet->row($this->getLineaAndSum(2),  [$this->data['info']['empresa_nombre'] . ' ' . $this->data['info']['empresa_ruc']]);

    $this->sheet->row($this->getLineaAndSum(),  [ 'Vendedor' , $this->data['info']['vendedor']]);
    $this->sheet->row($this->getLineaAndSum(),  [ 'Usuario: ' ,  $this->data['info']['usuario']]);
    $this->sheet->row($this->getLineaAndSum(),  [ 'Total de Cotizaciones: ' ,  $this->data['info']['cotiTotales']]);
    $this->sheet->row($this->getLineaAndSum(),  [ 'Zona: ' ,  $this->data['info']['zona']]);
    $this->sheet->row($this->getLineaAndSum(),  [ 'Estado: ' ,  $this->data['info']['estado']]);


    $this->sheet->row($this->getLineaAndSum(), $this->getHeader());
    $this->sheet->row($this->getLastLine(), function ($row) {
      $row->setFontWeight('bold');
    });

  }

  public function process()
  {
    $items = $this->data['items'];

    foreach ($items as $cotizacion) {

        $this->sheet->row($this->getLineaAndSum(), [
        $cotizacion['id'], 
        $cotizacion['estado'], 
        $cotizacion['fecha'], 
        $cotizacion['cliente_ruc'], 
        $cotizacion['cliente_cliente'], 
        $cotizacion['usuario'],
        $cotizacion['observacion'],
        $cotizacion['zona'],
        $cotizacion['total']
        ]);


      $this->sheet->row($this->getLastLine(), function ($row) {
        $row->setFontWeight('bold');
      });

      foreach ($cotizacion['items'] as $item) {
        
        $this->sheet->row($this->getLineaAndSum(), [
          $item['id'],
          $item['nombre'],
          $item['cantidad'],
          $item['importe']
        ]);
      }
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
