<?php

namespace App\Util\ExcellGenerator;


class IngresoEgresoExcell extends ExcellGenerator
{
  use LineTrait;

  protected $sheet;

  const HEADER = [
    "Nº Doc.",
    "Nombres",
    "Fecha",
    "S./",
    "$./",
    "Motivo",
    "Usuario",
  ];



  public $title_hoja = "";
  public $linea = 1;

  const TITLE_HOJA = "REPORTE ";

  protected $infoProductsInfo = [];

  public function __construct($data, $title_hoja = "hoja_excell")
  {
    // dd( $data );
    // exit();
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
    // dd( $this->data );
    // exit();
    $items = $this->data['data']['items'];
    

    foreach ($items as $item) {
      $this->sheet->row($lineStock = $this->getLineaAndSum(), [
        $item->id ,
        $item->nombre,
        $item->fecha ,
        $item->soles,
        $item->dolares,
        $item->motivo,
        $item->usuario,
      ]);
    }



    $this->sheet->row($line =  $this->getLineaAndSum(), [ 
      'Total' ,
      '', 
      '', 
      $this->data['data']['totalSoles'],
      $this->data['data']['totalDolares'],   
      ]);

    // Poner Negrita
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });

  }


  public function setHeaders()
  {
    $this->sheet->row($line =  $this->getLineaAndSum(), [$this->data['nombre_empresa']  . ' ' .  $this->data['ruc_empresa']]);
    $this->sheet->row($this->getLineaAndSum(), ['Fecha Desde',  $this->data['fechaDesde']]);
    $this->sheet->row($this->getLineaAndSum(), ['Fecha Hasta',  $this->data['fechaHasta']]);

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
