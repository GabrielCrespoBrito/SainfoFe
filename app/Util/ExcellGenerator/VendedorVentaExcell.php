<?php

namespace App\Util\ExcellGenerator;

use Illuminate\Database\Eloquent\Model;

class VendedorVentaExcell extends ExcellGenerator
{
  use LineTrait;
  protected $sheet;

  const HEADER = [
    "Nro Op.",
    "T.D",
    "Serie",
    "Número",
    "F.Emis.",
    "F.Venc.",
    "F.Pago",
    "Cliente",
    "Importe",
    "Pago",
    "Saldo",
    "Condición"
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
    $lines = [];
    $this->sheet->row( $this->getLineaAndSum(),  [ $this->data['info']['reporte_nombre'] ]);
    $this->sheet->row($this->getLineaAndSum(),  [ $this->data['info']['empresa_nombre'] . ' ' . $this->data['info']['empresa_ruc']   ]  );
    $this->sheet->row($this->getLineaAndSum(),  [ 'Vendedor:' ,  $this->data['info']['vendedor'] ]);
    $this->sheet->row($this->getLineaAndSum(),  [ 'Local:',  $this->data['info']['local']]);
    $this->sheet->row($this->getLineaAndSum(),  [ 'Cliente:',  $this->data['info']['cliente']]);
    $this->sheet->row($this->getLineaAndSum(),  [ 'Fecha Desde:',  $this->data['info']['fecha_desde']]);
    $this->sheet->row($this->getLineaAndSum(2),  [ 'Fecha Hasta:',  $this->data['info']['fecha_hasta']]);

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

    $total = 0;
    foreach ($items as $item) {

      $this->sheet->row( $line = $this->getLineaAndSum(),  [
        $item['info']['id'],
        $item['info']['nombre_complete']
      ]);

      $this->sheet->row( $line, function ($row) {
        $row->setFontWeight('bold');
      });

      foreach( $item['items'] as $venta ){
        $this->sheet->row($this->getLineaAndSum(), [
          $venta['info']['id'], 
          $venta['info']['tipo_documento'], 
          $venta['info']['serie'], 
          $venta['info']['numeracion'], 
          $venta['info']['fecha_emision'], 
          $venta['info']['fecha_vencimiento'], 
          $venta['info']['fecha_pago'], 
          $venta['info']['cliente'], 
          $venta['total']['importe'],
          $venta['total']['pago'],
          $venta['total']['saldo'],
          $venta['info']['forma_pago']
        ]);
      }

        $this->sheet->row($this->getLineaAndSum(), [
        $item['info']['id'],
        $item['info']['nombre_complete'],
          '',
          '',
          '',
          '',
          '',
          '',
          $item['total']['importe'],
          $item['total']['pago'],
          $item['total']['saldo'],
          '',
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
      '',
      '',
      $this->data['total']['importe'],
      $this->data['total']['pago'],
      $this->data['total']['saldo'],
      '',
    ]);

    $this->sheet->row($this->getLastLine(),
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
