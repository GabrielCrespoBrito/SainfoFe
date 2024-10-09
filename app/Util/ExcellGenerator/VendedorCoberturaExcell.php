<?php

namespace App\Util\ExcellGenerator;

use Illuminate\Database\Eloquent\Model;

class VendedorCoberturaExcell extends ExcellGenerator
{
  use LineTrait;
  protected $sheet;

  const HEADER = [
    "Cod. Vendedor",
    "Nomb. Vendedor", 
    "Unidades",
    "Soles",
    "Cobertura"
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


  public function processVendedores()
  {

    $vendedores = $this->data['items'];

    // dd( "Vendedores" , $vendedores );

    foreach ($vendedores as $vendedorId => $vendedorData) {

      // $this->sheet->row( $line = $this->getLineaAndSum(),  [
      //   $item['info']['id'],
      //   $item['info']['nombre_complete']
      // ]);

      // $this->sheet->row( $line, function ($row) {
      //   $row->setFontWeight('bold');
      // });

      // Iterar por cada cobertura
      foreach($vendedorData['items'] as $coberturaId => $cobertura ){

        // dd($cobertura);

        $this->sheet->row($this->getLineaAndSum(), [
          $vendedorData['info']['id'],
          $vendedorData['info']['nombre_complete'], 
          $cobertura['total']['cantidad'],
          $cobertura['total']['importe'],
          $cobertura['info']['cliente_codigo'],
        ]);

      }

        // Total Vendedor
        $this->sheet->row($this->getLineaAndSum(), [
          $vendedorId,
          'TOTAL VENDEDOR',
          $vendedorData['total']['cantidad'],
          $vendedorData['total']['importe'],
        ]);


        $this->sheet->row($this->getLastLine(), function ($row) {
          $row->setFontWeight('bold');
        });


      // $total += $producto['stock_final']['total'];
    }


    // Total General
    $this->sheet->row($this->getLineaAndSum(), [
      '',
      'TOTAL GENERAL',
      $this->data['total']['cantidad'],
      $this->data['total']['importe'],
      // $this->data['total']['importe'],
      // $this->data['total']['pago'],
      // $this->data['total']['saldo'],
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
      $this->processVendedores();
    });
  }
}
