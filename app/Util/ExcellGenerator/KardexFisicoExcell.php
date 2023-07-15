<?php

namespace App\Util\ExcellGenerator;


class KardexFisicoExcell extends ExcellGenerator
{
  use LineTrait;

  protected $sheet;

  const HEADER = [
    'Fecha',
    'Tipo Mov',
    'Alm',
    'Razón social',
    'N° Oper.',
    'Doc. Refe.',
    'Ingreso.',
    'Salida.',
    'Saldo.',
  ];

  public $title_hoja = "";
  public $linea = 1;

  const TITLE_HOJA = "REPORTE KARDEX FISICO";

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

      // Nombre de producto
      $this->sheet->row($lineHeader =  $this->getLineaAndSum(), [
        $producto['info']['id'],
        $producto['info']['codigo'],
        $producto['info']['nombre']]  
      );

      // Stock Inicial
      $this->sheet->row( $lineStock = $this->getLineaAndSum(), [
          $this->data['fecha_anterior'], '', '', '', '',
          'Stock Ini.',
          $producto['stock_inicial']['ingreso'],
          $producto['stock_inicial']['salida'],
          $producto['stock_inicial']['saldo'],
      ]);

      // Iteraciòn
      foreach( $producto['movimientos'] as $item ){
        $this->sheet->row( $this->getLineaAndSum(), [
          $item['fecha'],
          $item['motivo'],
          $item['almacen'],
          $item['razon_social'],
          $item['nro_operacion'],
          $item['documento_referencia'],
          $item['ingreso'],
          $item['salida'],
          $item['saldo'],
        ]);;
      }

      // Stock Total
      $this->sheet->row($lineTotal = $this->getLineaAndSum(), [
        '',
        '',
        '',
        '',
        '',
        'Totales',
        $producto['totales']['ingreso'],
        $producto['totales']['salida'],
        '', // $producto['totales']['saldo'],
      ]);

      $this->sheet->row($lineHeader, function ($row) {
        $row->setFontWeight('bold');
        $row->setBackground('#cccccc');

      });      

      $this->sheet->row($lineStock, function ($row) {
        $row->setFontWeight('bold');
      });
      $this->sheet->row($lineTotal, function ($row) {
        $row->setFontWeight('bold');
      });
    }
  }


  public function setHeaders()
  {
    $this->sheet->row($line =  $this->getLineaAndSum(), [$this->data['nombre_empresa']  . ' ' .  $this->data['ruc_empresa']]);
    $this->sheet->row($this->getLineaAndSum(), ['Fecha Desde',  $this->data['fecha_desde']]);
    $this->sheet->row($this->getLineaAndSum(), ['Fecha Hasta',  $this->data['fecha_hasta']]);
    $this->sheet->row($this->getLineaAndSum(), ['Local',  $this->data['nombre_local']]);

    // Poner Negrita
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });
  }


  public function setHeaderTable()
  {
    $this->sheet->row($line =  $this->getLineaAndSum(), $this->getHeader() );

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
