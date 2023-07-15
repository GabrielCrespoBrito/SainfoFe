<?php

namespace App\Jobs\Reporte;

use App\Marca;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\TipoDocumentoPago;
use App\Venta;
use App\Vendedor;
use App\VentaItem;
use Illuminate\Support\Facades\DB;

class ReporteVendedorProducto
{
  const REPORTE_NOMBRE  = "REPORTE DE VENDEDOR POR PRODUCTOS VENDIDOS";

  /**
   * Data del reporte
   */
  protected $data = [];

  protected $vendedor;
  protected $marca;
  protected $fecha_desde;
  protected $fecha_hasta;
  protected $cliente;
  protected $saldo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($vendedor, $marca, $fecha_desde, $fecha_hasta)
  {
    $this->vendedor = $vendedor;
    $this->marca = $marca;
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->handle();
  }

  /**
   * Execute the job.
   *
   */
  public function getQuery()
  {
    $query = DB::connection('tenant')->table('ventas_detalle')
      ->join('ventas_cab', function ($join) {
        $join->on('ventas_cab.VtaOper', '=', 'ventas_detalle.VtaOper');
      })
      ->join('productos', function ($join) {
        $join->on('productos.ProCodi', '=', 'ventas_detalle.DetCodi');
      })
      ->join('vendedores', function ($join) {
        $join->on('ventas_cab.Vencodi', '=', 'vendedores.Vencodi');
      })
      ->join('marca', function ($join) {
        $join->on('marca.MarCodi', '=', 'productos.marcodi');
      })
      ->whereBetween('ventas_cab.VtaFvta', [$this->fecha_desde, $this->fecha_hasta]);

    $query->whereIn('ventas_cab.TidCodi', [Venta::BOLETA, Venta::FACTURA, Venta::NOTA_DEBITO, Venta::NOTA_CREDITO, Venta::NOTA_VENTA]);

    if ($this->marca) {
      $query->where('productos.marcodi', $this->marca);
    }

    if ($this->vendedor) {
      $query->where('ventas_cab.Vencodi', $this->vendedor);
    }

    return $query->select(
      'ventas_cab.TidCodi as tipodocumento',
      'ventas_cab.VtaFMail as estado',
      'ventas_detalle.DetCodi as producto_codigo',
      'ventas_detalle.Detfact as factor',
      'ventas_detalle.DetCant as cantidad',
      'ventas_detalle.DetVSol as importe',
      'vendedores.Vencodi as vendedor_codigo',
      'vendedores.vennomb as vendedor_nombre',
      'vendedores.ventel1 as vendedor_telefono',
      'marca.MarNomb as marca_nombre',
      'productos.ProNomb as producto_nombre',
      'productos.unpcodi as unidad_nombre'
    )
      ->get()
      ->groupBy(['vendedor_codigo', 'producto_codigo']);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();

    // Si no existen registros, deter el resto del script
    if ($query->count() == 0) {
      return;
    }

    $data = [];
    $this->addToData($data, $this->getInfoReporte());
    $total_reporte = &$data['total'];
    $this->processAll($query, $data, $total_reporte);
    $this->data = $data;
  }

  /**
   * Procesar los dias por iterar
   *
   * @return void
   */
  public function processAll($vendedores_group, &$data, &$total_reporte)
  {
    foreach ($vendedores_group as $vendedor_id => $productos_group_vendedor) {

      $data['items'][$vendedor_id] = [];
      $add = &$data['items'][$vendedor_id];
      $producto_venta = $productos_group_vendedor->first()->first();

      $vendedor_info = [
        'id' => $producto_venta->vendedor_codigo,
        'nombre' => $producto_venta->vendedor_nombre,
        'nombre_complete' =>
        sprintf(
          "%s %s",
          $producto_venta->vendedor_nombre,
          $producto_venta->vendedor_telefono
        )
      ];

      $this->addToData($add, $vendedor_info);
      $total_vendedor = &$add['total'];
      $this->processProductos($productos_group_vendedor, $add, $data, $total_reporte, $total_vendedor);
    }
  }

  /**
   * Procesar el dia de la venta
   * 
   * @return void
   */
  public function processProductos($productos_group_vendedor, &$addToAdd, &$data, &$total_reporte, &$total_vendedor)
  {
    foreach ($productos_group_vendedor as $producto_id =>  $productos) {

      $add = &$addToAdd['items'][$producto_id];
      $this->addToData($add, $this->getInfoProducto($productos), false);
      $total_producto = &$add['total'];
      foreach ($productos as $producto) {
        $cantidad = 0;
        $importe = 0;
        
        if( $producto->estado != StatusCode::CODE_0002 && $producto->estado != StatusCode::CODE_0003 ){
          $convertNegative =$producto->tipodocumento == TipoDocumentoPago::NOTA_CREDITO;
          $cantidad = convertNegativeIfTrue($producto->cantidad * $producto->factor, $convertNegative);
          $importe  = convertNegativeIfTrue($producto->importe, $convertNegative);
        }

        $this->addToTotal($total_producto, $cantidad, $importe);
        $this->addToTotal($total_reporte, $cantidad, $importe);
        $this->addToTotal($total_vendedor, $cantidad, $importe);
      }
    }
  }

  public function addToTotal(&$total, $cantidad, $importe)
  {
    $total['cantidad'] += $cantidad;
    $total['importe'] += $importe;
  }

  /**
   * Establecer la data del reporte
   * 
   * @return array
   */
  public function addToData(&$arrAdd, $info = [], $addIndexItems = true)
  {
    // Info
    $arrAdd['info'] = $info;

    // Items
    if ($addIndexItems) {
      $arrAdd['items'] = [];
    }

    // Total
    $arrAdd['total'] = [
      'cantidad' => 0,
      'importe' => 0,
    ];

    return $arrAdd;
  }

  public function getInfoReporte()
  {
    $marca = $this->marca ? Marca::find($this->marca)->MarNomb : 'TODOS';
    $vendedor =  $this->vendedor ? Vendedor::find($this->vendedor)->LocNomb : 'TODOS';
    $empresa = get_empresa();
    return [
      'reporte_nombre' => self::REPORTE_NOMBRE,
      'empresa_nombre' => $empresa->nombre(),
      'empresa_ruc' => $empresa->ruc(),
      'fecha_generacion' => date('Y-m-d H:i:s'),
      'vendedor' => $vendedor,
      'marca' => $marca,
      'fecha_desde' => $this->fecha_desde,
      'fecha_hasta' => $this->fecha_hasta,
    ];
  }
  /**
   * Establecer la informaciòn del documento
   * 
   * @return array
   */
  public function getInfoProducto($productos)
  {
    $producto = $productos->first();
    return [
      "producto_codigo" => $producto->producto_codigo,
      "unidad_nombre" => $producto->unidad_nombre,
      "producto_nombre" => $producto->producto_nombre,
      "marca_nombre" => $producto->marca_nombre,
    ];
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
