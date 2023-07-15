<?php

namespace App\Util\ExcellGenerator;

class CajaMovimientoExcell extends ExcellGenerator
{
  use LineTrait;
  protected $sheet;

  const HEADER = [
    "Nº Doc",
    "Motivo",
    "Nombre",
    "Ingreso S/",
    "Egreso S/",
    "T.Cambio",
    "Ingreso $",
    "Egreso $",
  ];


  public $nombreEmpresa;
  public $linea = 1;

  public $periodo = "";
  public $title_hoja = "";
  public $fecha_inicio = "";
  public $fecha_final = "";

  const TITLE_HOJA = "REPORTE VENDEDOR-VENTAS";

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
    // -----------------------------------------------------------------------------------------------    
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['titulo']]);
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['nombre_empresa']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Caja:', $this->data['caja']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Usuario:', $this->data['usuario']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Fecha Apertura:', $this->data['fecha_apertura']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Fecha Cierre:', $this->data['fecha_cierre']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Estado:', $this->data['estado']]);
    $this->sheet->row($line =  $this->getLineaAndSum(), $this->getSaldoInicial());
    $this->sheet->row($this->getLineaAndSum(), $this->getHeader());
    $this->sheet->row($this->getLastLine(), function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row(1, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row(2, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
      $row->setBackground('#cccccc');
    });
  }

  public function getSaldoInicial()
  {
    return [
      'Saldo Inicial:',
      '',
      '',
      '',
      '',
      '',
      'S./ ' .  $this->data['data']['saldo_inicial_sol'],
      'USD./' . $this->data['data']['saldo_inicial_dolar'],
    ];
  }



  public function getSaldoFinal()
  {
    return [
      'Saldo Final:',
      '',
      '',
      '',
      '',
      '',
      'S./ ' .  $this->data['data']['saldo_final_sol'],
      'USD./' . $this->data['data']['saldo_final_dolar'],
    ];
  }


  public function getTotales()
  {
    return [
      'Totales:',
      '',
      '',
      $this->data['data']['total_ingreso_sol'],
      $this->data['data']['total_egreso_sol'],
      '',
      $this->data['data']['total_ingreso_dolar'],
      $this->data['data']['saldo_final_dolar'],
    ];
  }

  public function processItems()
  {
    $items = $this->data['data']['items'];
    foreach ($items as $item) {
      $this->sheet->row($this->getLineaAndSum(), [
        $item['nro_documento'],
        $item['motivo'],
        $item['nombre'],
        $item['ingreso_sol'],
        $item['egreso_sol'],
        $item['tipo_cambio'],
        $item['ingreso_dolar'],
        $item['egreso_dolar'],
      ]);
    }

    $this->sheet->row($line = $this->getLineaAndSum(), $this->getTotales());
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });      
    $this->sheet->row( $line = $this->getLineaAndSum(), $this->getSaldoFinal());
    
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
      $row->setBackground('#cccccc');
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
      $this->processItems();
    });
  }
}
