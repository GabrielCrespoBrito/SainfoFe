<?php

namespace App\Jobs\Producto;

use Illuminate\Support\Facades\DB;

class ProductoMasVendidoReport
{
  public $data;
  public $fecha_desde;
  public $fecha_hasta;
  public $local;


  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($fecha_desde, $fecha_hasta, $local = null)
  {
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->local = $local;

    $this->handle();
  }

  public function setData($data)
  {
    $this->data = $data;
  }

  public function getData()
  {
    return $this->data;
  }

  public function getQuery()
  {
    $group_productos =
      DB::connection('tenant')->table('ventas_detalle')
      ->join('ventas_cab', 'ventas_cab.VtaOper', '=', 'ventas_detalle.VtaOper')
      ->join('productos', 'productos.ProCodi', '=', 'ventas_detalle.DetCodi')
      ->join('unidad', 'unidad.Unicodi', '=', 'ventas_detalle.UniCodi')
      ->where('ventas_detalle.DetCodi', '!=' , 'P' )
      ->whereBetween('ventas_cab.VtaFvta', [$this->fecha_desde, $this->fecha_hasta]);


    if($this->local){
      $group_productos->where('ventas_cab.LocCodi', $this->local);
    }

    return $group_productos
      ->select([
        'productos.ProNomb as nombre',
        'ventas_detalle.DetCodi as codigo_producto',
        'ventas_detalle.Unicodi as unidad',
        'ventas_detalle.MarNomb as marca',
        'ventas_detalle.DetCant as cantidad',
        'unidad.UniEnte as unidad_entero',
        'unidad.UniMedi as unidad_medida',
      ])
      ->get()
      ->groupBy(['codigo_producto', 'unidad']);
  }


  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $group_productos = $this->getQuery();
    
    $data = [];
    foreach ( $group_productos as $productoID => $group_unidades ) {
      $data[$productoID] = [
        'nombre'   => '',
        'codigo'   => $productoID,
        'unidad'   => '',
        'marca'    => '',
        'cantidad' => 0,
      ];

      foreach ($group_unidades as $productos_unidad ) {
        $producto_data = $productos_unidad->first();
        $data[$productoID]['nombre'] = $producto_data->nombre;
        $data[$productoID]['codigo_producto'] = $producto_data->codigo_producto;
        $data[$productoID]['unidad'] = $producto_data->unidad;
        $data[$productoID]['marca'] = $producto_data->marca;
        $data[$productoID]['cantidad'] += get_real_quantity( $producto_data->unidad_entero, $producto_data->unidad_medida , $productos_unidad->sum('cantidad') );
      }
    }

    $this->setData(collect($data)->sortByDesc('cantidad'));
  }
}
