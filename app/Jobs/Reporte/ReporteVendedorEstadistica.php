<?php

namespace App\Jobs\Reporte;

use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Venta;
use App\Moneda;
use App\TipoDocumentoPago;
use Illuminate\Support\Facades\DB;

class ReporteVendedorEstadistica
{
  const REPORTE_NOMBRE  = "RANKING DE VENTAS POR VENDEDOR";

  protected $data = [];
  protected $fecha_desde;
  protected $fecha_hasta;

  public function __construct($fecha_desde, $fecha_hasta)
  {
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->handle();
  }

  public function getQuery()
  {
    $query = DB::connection('tenant')->table('ventas_cab')
      ->join('vendedores', function ($join) {        
        $join->on('ventas_cab.Vencodi', '=', 'vendedores.Vencodi');
      })
      // ->where( 'ventas_cab.VtaEsta', 'V')
      ->whereNotIn( 'ventas_cab.VtaFMail', [ StatusCode::CODE_0002, StatusCode::CODE_0003 ] )
      ->whereBetween( 'ventas_cab.VtaFvta' , [$this->fecha_desde, $this->fecha_hasta]);

    $query->whereIn('ventas_cab.TidCodi', [
      Venta::BOLETA,
      Venta::FACTURA,
      Venta::NOTA_DEBITO,
      Venta::NOTA_VENTA,
      Venta::NOTA_CREDITO,
    ]);

    return $query->select(
      'ventas_cab.Vtabase as importe',
      'ventas_cab.VtaTcam as tipo_cambio',
      'ventas_cab.MonCodi as moneda',
      'ventas_cab.TidCodi as tipodocumento',
      'vendedores.Vencodi as vendedor_codigo',
      'vendedores.vennomb as vendedor_nombre',
      'vendedores.ventel1 as vendedor_telefono',
    )->get()
    ->groupBy(['vendedor_codigo']);
  }

  public function handle()
  {
    $query =  $this->getQuery();
    if ($query->count() == 0) {
      return;
    }

    $data = [];
    $this->addToData($data, $this->getInfoReporte());
    $total_reporte = &$data['total'];
    $this->processAll($query, $data, $total_reporte);
    $this->data = $data;
  }

  public function processAll($vendedores_group, &$data, &$total_reporte)
  {
    foreach ( $vendedores_group as $vendedor_id => $ventas ) {
      $data['items'][$vendedor_id] = [];
      $add = &$data['items'][$vendedor_id];
      $producto_venta = $ventas->first();
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

      $this->addToData($add, $vendedor_info, false);
      $total_vendedor = &$add['total'];
      $this->processVentas($ventas, $add, $total_reporte, $total_vendedor);
    }
  }

  public function processVentas($ventas, &$addToAdd, &$total_reporte, &$total_vendedor)
  {
    foreach ($ventas as $venta) {
      $this->addToTotal($total_reporte, $venta);
      $this->addToTotal($total_vendedor, $venta);
    }
  }

  public function addToTotal(&$total, $venta)
  {
    $importe = $venta->moneda == Moneda::SOL_ID ? $venta->importe : ($venta->importe * $venta->tipo_cambio);
    $total['importe'] += convertNegativeIfTrue($importe,  $venta->tipodocumento == TipoDocumentoPago::NOTA_CREDITO );
  }

  public function addToData(&$arrAdd, $info = [], $addIndexItems = true)
  {
    # Info
    $arrAdd['info'] = $info;

    # Items
    if ($addIndexItems) {
      $arrAdd['items'] = [];
    }

    # Total
    $arrAdd['total'] = ['importe' => 0];
    return $arrAdd;
  }

  public function getInfoReporte()
  {
    $empresa = get_empresa();
    return [
      'reporte_nombre' => self::REPORTE_NOMBRE,
      'empresa_nombre' => $empresa->nombre(),
      'empresa_ruc' => $empresa->ruc(),
      'fecha_generacion' => date('Y-m-d H:i:s'),
      'fecha_desde' => $this->fecha_desde,
      'fecha_hasta' => $this->fecha_hasta,
    ];
  }

  public function getData()
  {
    return $this->data;
  }
}