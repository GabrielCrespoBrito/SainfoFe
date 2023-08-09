<?php

namespace App\Util\ExcellGenerator;

class VendedorVentaProductoExcell extends ExcellGenerator
{
  use LineTrait;
    
  protected $sheet;
  const HEADER = [
    "Código",
    "Unidad",
    "Descripción",
    "Tipo Documento",
    "N° Documento",
    "Cod. Cliente",
    "Marca",
    "Cantidad",
    "Importe",
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
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['info']['empresa_nombre'] . ' ' . $this->data['info']['empresa_ruc']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Vendedor:',  $this->data['info']['vendedor']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Marca:',  $this->data['info']['marca']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Fecha Desde:',  $this->data['info']['fecha_desde']]);
    $this->sheet->row($this->getLineaAndSum(2),  ['Fecha Hasta:',  $this->data['info']['fecha_hasta']]);

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

    $this->sheet->row(3, function ($row) {
      $row->setFontWeight('bold');
    });


    $this->sheet->row(4, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row(5, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row(6, function ($row) {
      $row->setFontWeight('bold');
    });
    $this->sheet->row(7, function ($row) {
      $row->setFontWeight('bold');
    });
  }

  public function processVentas()
  {
    $items = $this->data['items'];

    foreach ($items as $item) {

      $this->sheet->row($line = $this->getLineaAndSum(),  [
        $item['info']['id'],
        $item['info']['nombre_complete']
      ]);

      $this->sheet->row($line, function ($row) {
        $row->setFontWeight('bold');
      });

      foreach ($item['items'] as $producto) {
        
        $this->sheet->row($this->getLineaAndSum(), [
          $producto['producto_codigo'],
          $producto['unidad_nombre'],
          $producto['producto_nombre'],
          $producto['tipodocumento'],
          $producto['numero_documento'] . ' ' . $producto['fecha_emision'],
          $producto['codigo_cliente'],
          $producto['marca_nombre'],
          $producto['cantidad'],
          $producto['importe'],
        ]);
      }

      $this->sheet->row($this->getLineaAndSum(), [
        $item['info']['id'],
        $item['info']['nombre_complete'],
        '',
        '',
        '',
        '',
        $item['total']['cantidad'],
        $item['total']['importe'],
      ]);


      $this->sheet->row($this->getLastLine(), function ($row) {
        $row->setFontWeight('bold');
      });


      // $total += $producto['stock_final']['total'];
    }

    $this->sheet->row($this->getLineaAndSum(), [
      '',
      '',
      '',
      '',
      '',
      '',
      $this->data['total']['cantidad'],
      $this->data['total']['importe'],
    ]);

    $this->sheet->row(
      $this->getLastLine(),
      function ($row) {
        $row->setFontWeight('bold');
      }
    );
  }


  public function procces(&$excel)
  {
    $excel->sheet($this->getSheetTitle(), function ($sheet) {

      $this->sheet = $sheet;
      $this->sheet->setAutoSize(false);
      $this->sheet->setFontFamily('Arial');
      $this->sheet->setFontSize(10);

      $this->headerSheet();
      $this->processVentas();
    });
  }
}
