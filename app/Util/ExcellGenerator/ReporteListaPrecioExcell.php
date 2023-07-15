<?php

namespace App\Util\ExcellGenerator;

use Illuminate\Database\Eloquent\Model;

namespace App\Util\ExcellGenerator;

use App\TipoPago;

class ReporteListaPrecioExcell extends ExcellGenerator
{
  use LineTrait;

  protected $sheet;


  public $nombreEmpresa;
  public $linea = 1;

  public $title_hoja = "";
  public $fecha_inicio = "";
  public $fecha_final = "";

  const TITLE_HOJA = "REPORTE LISTA DE PRECIOS";

  public function __construct($data, $title_hoja = "reporte_por_tipo_pago")
  {
    parent::__construct($data, true, null);
    $this->title_hoja = $title_hoja;
  }
  
  public function getSheetTitle()
  {
    return $this->title_hoja;
  }

  public function headerSheet()
  {
    $this->sheet->row($lineTitulo = $this->getLineaAndSum(),  [strtoupper($this->data['nombre_reporte'])]);
    $this->sheet->row($lineEmpresa = $this->getLineaAndSum(),  [$this->data['nombre_empresa'] . ' ' . $this->data['ruc']]);
    $this->sheet->row( $lineLocal = $this->getLineaAndSum(),  ['Local:', $this->data['nombre_local']  ]);
    $this->sheet->row( $lineLista =  $this->getLineaAndSum(),  ['Lista:', $this->data['nombre_lista']]);
    
    $this->sumLinea();

    $this->sheet->row($lineTitulo, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row($lineEmpresa, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row($lineLocal, function ($row) {
      $row->setFontWeight('bold');
    });

    $this->sheet->row($lineLista, function ($row) {
      $row->setFontWeight('bold');
    });    

  }
  public function  getHeaderTable()
  {
    $headerTable = [
    'Nro Op.',
    'Codigo',
    'Unidad',
    'Descripción',
    'Marca',
    'Peso',
    ];

    if( $this->data['costo'] ){
      array_push($headerTable, 'Costo US$', 'Costo S/', 'Margen');
    }
    
    array_push( $headerTable, 'Precio US$', 'Precio S/', 'Stock' );
  
    $this->sheet->row($line = $this->getLineaAndSum(), $headerTable);
    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });
  }

  public function  getHeaderGrupo($codigo,$nombre)
  {
    $this->sheet->row($line = $this->getLineaAndSum(),  [$codigo, $nombre]);

    $this->sheet->row($line, function ($row) {
      $row->setFontWeight('bold');
    });    
  }

  
  

  public function processItems()
  {
    $productos_group = $this->data['productos_group'];

    $this->getHeaderTable();

    foreach ($productos_group as $items ) {

      $item = $items->first();

      $this->getHeaderGrupo($item->GruCodi, $item->GruNomb);

      /*
        <tr>
          <td>{{ $producto->Id }} </td> 
          <td>{{ $producto->ProCodi }} </td> 
          <td>{{ $producto->UniAbre }} </td> 
          <td>{{ $producto->ProNomb }} </td> 
          <td>{{ $producto->MarNomb }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UniPeso) }} </td> 
          @if($costo == 1)
          <td style="text-align:right">{{ fixedValue($producto->UniPUCD) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UniPUCS) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UniMarg) }} </td> 
          @endif
          <td style="text-align:right">{{ fixedValue($producto->UNIPUVD) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->UNIPUVS) }} </td> 
          <td style="text-align:right">{{ fixedValue($producto->stock) }} </td> 
        </tr>
      */

      
      
      foreach ($items as $item) {

        $itemData = [
          $item->Id,
          $item->ProCodi,
          $item->UniAbre,
          $item->ProNomb,
          $item->MarNomb,
          $item->UniPeso
        ];
  
        if( $this->data['costo'] ){
          array_push($itemData, $item->UniPUCD, $item->UniPUCS, $item->UniMarg );
        }
  
        array_push($itemData, $item->UNIPUVD, $item->UNIPUVS, $item->stock);
        $this->sheet->row($this->getLineaAndSum(), $itemData );
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
      $this->processItems();
    });
  }
}
