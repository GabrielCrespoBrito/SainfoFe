<?php

namespace App\Jobs\Reporte;

use App\Venta;
use Illuminate\Support\Facades\Log;

class ReporteUtilidades
{
  /**
   * Data del reporte
   */
  protected $data = [];

  protected $current_fecha;
  protected $fecha_desde;
  protected $fecha_hasta;
  protected $local;
  protected $grupo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct( $fecha_desde, $fecha_hasta, $local, $grupo )
  {
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->local = strtolower($local) == "todos" ? null : $local;
    $this->grupo = strtolower($grupo) == "todos" ? null : $grupo;
    $this->handle();
  }

  /**
   * Execute the job.
   *
   */
  public function getQuery()
  {
    $grupo = $this->grupo;

    $query = Venta::with([ 'cliente_with' => function($query){
      $query->where('TipCodi', 'C');
    }, 'items.producto'  => function($query) use ($grupo) {
      if( $grupo ){
        $query->where('grucodi', '=' ,  $grupo);
      }
    } ])
    ->whereBetween('VtaFvta',[ $this->fecha_desde , $this->fecha_hasta ]);


    if( $this->local ){
      $query->where('LocCodi', $this->local );
    }

    // dd( $query->get()->first() );
    // exit();
      
    return
    $query
    ->orderBy('TidCodi')
    ->get()
    ->groupBy('VtaFvta');
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();
    $data = [];
    $this->addToData($data, [ 'count' => count($query) ]);
    $total_reporte = &$data['total'];
    $this->processAll($query, $data, $total_reporte );
    $this->setPositiveUtilidad($data);
    $this->data = $data;
  }

  /**
   * Procesar los dias por iterar
   *
   * @return void
   */
  public function processAll($ventas_group_fechas, &$data, &$total_reporte)
  {
    foreach ($ventas_group_fechas as $fecha => $ventas_fecha){      
      $this->current_fecha = $fecha;
      $data['items'][$fecha] = [];
      $add = &$data['items'][$fecha];
      $this->addToData($add, ['data' => $fecha, 'count' => count($ventas_fecha) ]); 
      $total_dia = &$add['total'];
      $this->processDia( $ventas_fecha, $add , $data , $total_reporte, $total_dia );
      $this->setPositiveUtilidad($add);
    }
  }

  /**
   * Procesar el dia de la venta
   * 
   * @return void
   */
  public function processDia($ventas_fecha, &$addToAdd, &$data, &$total_reporte, &$total_dia )
  {
    foreach( $ventas_fecha as $venta ){
      $add = &$addToAdd['items'][$venta->VtaOper];      
      $this->addToData($add,  $this->getInfoVenta($venta));
      $total_venta = &$add['total'];      
      $this->processVenta($venta,  $add , $data, $total_reporte, $total_dia, $total_venta );
      $this->setPositiveUtilidad($add);
    }
  }

  /**
   * Procesar la venta especifica
   * 
   */
  public function processVenta($venta, &$arrAdd, &$data, &$total_reporte, &$total_dia, &$total_venta )
  {
    $items  = $venta->items;
    foreach ( $items as $item ) {
      if( $item->producto == null ){
        continue;
      }

      $add = &$arrAdd['items'][$item->Linea];
      $this->addToData($add, $this->getInfoItem($item) , false);
      $total_item = &$add['total'];      
      $this->processItem($item, $arrAdd, $data , $total_reporte, $total_dia, $total_venta, $total_item );
      $this->setPositiveUtilidad($add);
    }

  }

  /**
   * Procesar el item de la venta
   * 
   * @return void
   */
  public function processItem($item, &$arrAdd, &$data , &$total_reporte, &$total_dia, &$total_venta, &$total_item )
  {
    $data_utilidad = (object) $item->getDataUtilidadProducto();

    $this->addToTotal( $total_reporte, $data_utilidad );
    $this->addToTotal( $total_dia, $data_utilidad );
    $this->addToTotal( $total_venta, $data_utilidad );
    $this->addToTotal( $total_item, $data_utilidad) ;
  }

  public function addToTotal( &$total, $data_utilidad )
  {
    $total['costo_soles'] += $data_utilidad->costo_soles;
    $total['costo_dolar'] += $data_utilidad->costo_dolar;
    $total['venta_soles'] += $data_utilidad->venta_soles;
    $total['venta_dolar'] += $data_utilidad->venta_dolar;
    $total['utilidad_soles'] += $data_utilidad->utilidad_soles;
    $total['utilidad_dolar'] += $data_utilidad->utilidad_dolar;    
  }

  /**
   * Establecer la data del reporte
   * 
   * @return array
   */
  public function addToData(&$arrAdd, $info = [], $addIndexItems = true )
  {
    // Info
    $arrAdd['info'] = $info;

    // Items
    if($addIndexItems){
      $arrAdd['items'] = [];
    }

    // Total
    $arrAdd['total'] = [
      'costo_soles' => 0,
      'costo_dolar' => 0,
      'venta_soles' => 0,
      'venta_dolar' => 0,
      'utilidad_soles' => 0,
      'utilidad_dolar' => 0,
    ];

    return $arrAdd;
  }

  /**
   * Establecer la informaciòn del item
   * 
   * @return array
   */
  public function getInfoItem($item)
  {
    return [
      'Linea' => $item->Linea,
      'base' => $item->DetBase,
      'name' => $item->DetNomb,
      'codigo' => $item->DetCodi,
      'unidad' => $item->DetUnid,
      'descripcion' => $item->DetNomb,
      'cantidad' => $item->DetCant,
      'precio' => $item->DetCSol / $item->DetCant,
      'importe' => $item->DetCSol,
    ];
  }


  /**
   * Establecer la informaciòn del documento
   * 
   * @return array
   */
  public function getInfoVenta($venta)
  {
    return[ 
      'data' => $venta->VtaNume,
      'route' => route('ventas.show', $venta->VtaOper ),
      'cliente' => $venta->cliente_with->PCNomb,
      'cliente_ruc' => $venta->cliente_with->PCRucc,
      'count' => count($venta->items)
    ];
  }

  /**
   * Establecer la informaciòn del item
   * 
   * @return array
   */
  public function setPositiveUtilidad(&$data)
  {
    $data['info']['positive'] = math()->isPositive($data['total']['utilidad_soles']);
  }

  /**
   * Obtener la data del reporte
   * 
   * @return array
   */
  public function getData()
  {
    return $this->data;
  }
}