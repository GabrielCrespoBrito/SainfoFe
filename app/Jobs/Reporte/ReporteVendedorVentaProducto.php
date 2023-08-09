<?php

namespace App\Jobs\Reporte;

use App\Marca;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\TipoDocumentoPago;
use App\Venta;
use App\Vendedor;
use App\VentaItem;
use Illuminate\Support\Facades\DB;

class ReporteVendedorVentaProducto
{
  const REPORTE_NOMBRE  = "REPORTE DE VENDEDOR POR VENTAS-PRODUCTOS VENDIDOS";

  /**
   * Data del reporte
   */
  protected $data = [];

  protected $vendedor;
  protected $tipodocumento_id;
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
  public function __construct($vendedor, $tipodocumento_id, $marca, $fecha_desde, $fecha_hasta)
  {
    $this->vendedor = $vendedor;
    $this->tipodocumento_id = $tipodocumento_id;
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
      ->whereBetween('ventas_cab.VtaFvta', [ $this->fecha_desde, $this->fecha_hasta ]);

    $tiposDoc = $this->tipodocumento_id ?
      [$this->tipodocumento_id] :
      [Venta::BOLETA, Venta::FACTURA, Venta::NOTA_DEBITO, Venta::NOTA_CREDITO, Venta::NOTA_VENTA];

    $query->whereIn('ventas_cab.TidCodi', $tiposDoc);

    if ($this->marca) {
      $query->where('productos.marcodi', $this->marca);
    }

    if ($this->vendedor) {
      $query->where('ventas_cab.Vencodi', $this->vendedor);
    }

    return $query->select(
      'ventas_cab.PCCOdi as codigo_cliente',
      'ventas_cab.VtaNume as numero',
      'ventas_cab.TidCodi as tipodocumento',
      'ventas_cab.VtaFvta as fecha_emision',
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
    ->orderBy('numero')
      ->get()
      ->groupBy(['vendedor_codigo']);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();

    // Si no existen registros, detener el resto del script
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
   * Iterar los vendedores
   *
   * @return void
   */
  public function processAll($vendedores_group,&$data,
    &$total_reporte ) {
      
    foreach ($vendedores_group as $vendedor_id => $productos) {

      $data['items'][$vendedor_id] = [];
      $add = &$data['items'][$vendedor_id];
      $producto_venta = $productos->first();
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
      $this->processProductos($productos, $add, $data, $total_reporte, $total_vendedor);
    }
  }

  /**
   * Procesar el dia de la venta
   * 
   * @return void
   */
  public function processProductos($productos, &$addToAdd, &$data, &$total_reporte, &$total_vendedor)
  {
    $add = &$addToAdd['items'];

    foreach ($productos as $producto_id =>  $producto) {
 
      $dataProducto = $this->getInfoProducto($producto);
      
      $cantidad = 0;
      $importe = 0;
      
      if ($producto->estado != StatusCode::CODE_0002 && $producto->estado != StatusCode::CODE_0003) {
        $convertNegative = $producto->tipodocumento == TipoDocumentoPago::NOTA_CREDITO;
        $cantidad = convertNegativeIfTrue($producto->cantidad * $producto->factor, $convertNegative);
        $importe  = convertNegativeIfTrue($producto->importe, $convertNegative);
      }

      $dataProducto['cantidad'] = $cantidad;
      $dataProducto['importe'] = $importe;
      array_push( $add , $dataProducto );
      
      // $this->addToTotal($total_producto, $cantidad, $importe);
      $this->addToTotal($total_reporte, $cantidad, $importe);
      $this->addToTotal($total_vendedor, $cantidad, $importe);
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
    $vendedor =  $this->vendedor ? Vendedor::find($this->vendedor)->vennomb : 'TODOS';
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
  public function getInfoProducto($producto)
  {
    // Marilyd
    // 931172985
    return [
      "tipodocumento" => $producto->tipodocumento,
      "codigo_cliente" => $producto->codigo_cliente,
      "numero_documento" => $producto->numero,
      "fecha_emision" => $producto->fecha_emision,
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
