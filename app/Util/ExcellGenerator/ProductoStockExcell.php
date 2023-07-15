<?php

namespace App\Util\ExcellGenerator;


class ProductoStockExcell extends ExcellGenerator
{
  use LineTrait;

  protected $sheet;

  const HEADER = [
    'ID',
    'Codigo',
    'Unidad',
    'Descripciòn',
    // 'Marca.',
    'Stock',
  ];

  public $title_hoja = "";
  public $linea = 1;

  const TITLE_HOJA = "REPORTE PRODUCTO STOCK";

  protected $infoProductsInfo = [];

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

  public function processProductos()
  {
    $items = $this->data['data'];
    foreach ($items as $producto) {
      $this->sheet->row($lineStock = $this->getLineaAndSum(), [
        $producto->id,
        $producto->codigo,
        $producto->unidad,
        $producto->nombre,
        // $producto->marca,
        // $producto->stock_minimo,
        $producto->stock_total,
      ]);


    }
  }


  public function setHeaders()
  {
    $this->sheet->row($line =  $this->getLineaAndSum(), [$this->data['nombre_empresa']  . ' ' .  $this->data['ruc_empresa']]);
    $this->sheet->row($this->getLineaAndSum(), ['Local',  $this->data['nombreLocal']]);
    $this->sheet->row($this->getLineaAndSum(), ['Grupo',  $this->data['nombreGrupo']]);
    $this->sheet->row($this->getLineaAndSum(), ['Familia',  $this->data['nombreFamilia']]);
    $this->sheet->row($this->getLineaAndSum(), ['Marca',  $this->data['nombreMarca']]);

    // Poner Negrita
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });
  }


  public function setHeaderTable()
  {
    $this->sheet->row($line =  $this->getLineaAndSum(), $this->getHeader());

    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });
  }


  public function setHeaderProduct()
  {
    $this->sheet->row($line =  $this->getLineaAndSum(), $this->getHeader());

    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });
  }

  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {
      $sheet->setAutoSize(false);
      $sheet->setFontFamily('Arial');
      $sheet->setFontSize(10);
      $this->sheet = $sheet;
      $this->setHeaders();
      $this->setHeaderTable();
      $this->processProductos();
    });
  }
}
