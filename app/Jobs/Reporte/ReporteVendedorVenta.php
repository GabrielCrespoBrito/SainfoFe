<?php

namespace App\Jobs\Reporte;

use App\ClienteProveedor;
use App\Local;
use App\Venta;
use App\Vendedor;

class ReporteVendedorVenta
{

  const REPORTE_NOMBRE  = "REPORTE DE REGISTRO DE VENTA POR VENDEDOR";

  /**
   * Data del reporte
   */
  protected $data = [];
  protected $current_fecha;
  protected $vendedor;
  protected $local;
  protected $fecha_desde;
  protected $fecha_hasta;
  protected $cliente;
  protected $saldo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($vendedor, $local, $fecha_desde, $fecha_hasta, $cliente, $saldo)
  {
    $this->vendedor = $vendedor;
    $this->local = $local;
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->cliente = $cliente;
    $this->saldo = $saldo;
    $this->handle();
  }

  /**
   * Execute the job.
   *
   */
  public function getQuery()
  {
    $query = Venta::with(['cliente_with' => function ($query) {
      $query->where('TipCodi', 'C');
    }, 'vendedor' => function($query){
      $query->withoutGlobalScopes();
    }, 'forma_pago'])
      ->whereBetween('VtaFvta', [$this->fecha_desde, $this->fecha_hasta])
      ->whereIn('TidCodi', [ Venta::BOLETA, Venta::FACTURA, Venta::NOTA_DEBITO,  Venta::NOTA_CREDITO, Venta::NOTA_VENTA ]);

    if ($this->local) {
      $query->where('LocCodi', $this->local);
    }

    if ($this->cliente) {
      $query->where('PCCodi', $this->cliente );
    }

    if ($this->vendedor) {
      $query->where('Vencodi', $this->vendedor);
    }

   if ($this->vendedor) {
      $query->where('Vencodi', $this->vendedor);
    }

    if ($this->saldo) {
      $query->where('VtaSald', '>' , 0 );
    }

    return
      $query
      ->get()
      ->groupBy('Vencodi');
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();
    
    // _dd( $query->first()->first() );
    // exit();

    // Si no existen registros, deter el resto del script
    if( $query->count() === 0 ){
      return;
    }

    $data = [];
    $this->addToData($data, $this->getInfoReporte() );
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
    // foreach ($vendedores_group as $fecha => $ventas_fecha) {
    foreach ($vendedores_group as $vendedor_id => $ventas_vendedor ) {
      
      $this->current_fecha = $vendedor_id;
      $data['items'][$vendedor_id] = [];
      $add = &$data['items'][$vendedor_id];
      $vendedor = $ventas_vendedor->first()->vendedor; 
      $vendedor_info = [
        'id' => $vendedor_id,
        'nombre' => $vendedor->vennomb,
        'nombre_complete' => 
        sprintf("%s %s", 
          $vendedor->vennomb,
          $vendedor->ventel1),
        'count_ventas' => count($ventas_vendedor),
      ];

      $this->addToData($add, $vendedor_info);
      $total_vendedor = &$add['total'];
      $this->processVendedor($ventas_vendedor, $add, $data, $total_reporte, $total_vendedor);
    }
  }

  /**
   * Procesar el dia de la venta
   * 
   * @return void
   */
  public function processVendedor($ventas_vendedores, &$addToAdd, &$data, &$total_reporte, &$total_vendedor)
  {
    foreach ($ventas_vendedores as $venta) {
      $add = &$addToAdd['items'][$venta->VtaOper];
      $this->addToData($add,  $this->getInfoVenta($venta),false);
      $total_venta = &$add['total'];
      $data_utilidad = (object) $venta->getTotalesPago();
      $this->addToTotal($total_reporte, $data_utilidad);
      $this->addToTotal($total_vendedor, $data_utilidad);
      $this->addToTotal($total_venta, $data_utilidad);
    }
  }

  public function addToTotal(&$total, $data_utilidad)
  {
    $total['importe'] += $data_utilidad->importe;
    $total['pago'] += $data_utilidad->pago;
    $total['saldo'] += $data_utilidad->saldo;
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
      'importe' => 0,
      'pago' => 0,
      'saldo' => 0,
    ];

    return $arrAdd;
  }

  public function getInfoReporte()
  {
    $local = $this->local ? Local::find($this->local)->LocNomb : 'TODOS';
    $vendedor =  $this->vendedor ? Vendedor::find($this->vendedor)->LocNomb : 'TODOS';
    $cliente = $this->cliente ? ClienteProveedor::findCliente($this->cliente)->PCNomb : 'TODOS';
    $empresa = get_empresa();
    return [
      'reporte_nombre' => self::REPORTE_NOMBRE,
      'empresa_nombre' => $empresa->nombre(),
      'empresa_ruc' => $empresa->ruc(),
      'fecha_generacion' => date('Y-m-d H:i:s'),
      'vendedor' => $vendedor,
      'local' => $local,
      'fecha_desde' => $this->fecha_desde,
      'fecha_hasta' => $this->fecha_hasta,
      'cliente' => $cliente,
    ];
  }
  /**
   * Establecer la informaciòn del documento
   * 
   * @return array
   */
  public function getInfoVenta($venta)
  {
    return [
      'data' => $venta->VtaNume,
      'id' => $venta->VtaOper,
      'tipo_documento' => $venta->TidCodi,
      'serie' => $venta->VtaSeri,
      'numeracion' => $venta->VtaNumee,
      'fecha_emision' => $venta->VtaFvta,
      'fecha_vencimiento' => $venta->VtaFVen,
      'fecha_pago' => $venta->vtaFpag,
      'forma_pago' => $venta->forma_pago->connomb,
      'cliente' => $venta->cliente_with->PCNomb,
      'cliente_ruc' => $venta->cliente_with->PCRucc,
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