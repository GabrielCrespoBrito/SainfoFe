<?php

namespace App\Util\ExcellGenerator;

class CotizacionExcell extends ExcellGenerator
{
  use LineTrait;
    
  protected $sheet;
// <thead class=" text-uppercase">
//   <tr>
//     <td style="font-weight:bold" width="7%"> Nº  </td>
//     <td style="font-weight:bold" width="7%"> ESTADO  </td>
//     <td style="font-weight:bold" width="5%"> FECHA </td>
//     <td style="font-weight:bold" width="7%"> DOC </td>
//     <td style="font-weight:bold" width="7%"> NOMBRE </td>
//     <td style="font-weight:bold" class="text-align-right" width="8%"> TOTAL </td>
//   </tr>
//   <tr>
//     <td> CODIGO </td>
//     <td style="font-weight:bold" colspan="2"> NOMBRE </td>
//     <td style="font-weight:bold" class="text-align-right"> CANTIDAD </td>
//     <td style="font-weight:bold" colspan="2" class="text-align-right"> IMPORTE </td>
//   </tr>
// </thead>

  const HEADER = [
    "Nº  ",
    "ESTADO  ",
    "FECHA ",
    "DOC ",
    "NOMBRE ",
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

    //  <td style="background-color: #fafafa;border-left:2px solid gray; padding-left:2px"> {{ $item['id'] }} </td>
    //   <td colspan="2" style="background-color: #fafafa;"> {{ $item['nombre'] }} </td>
    //   <td style="background-color: #fafafa;" class="text-align-right"> {{ $item['cantidad'] }}  </td>
    //   <td style="background-color: #fafafa;"  class="text-align-right" colspan="2"> {{ $item['importe'] }}  </td>

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
