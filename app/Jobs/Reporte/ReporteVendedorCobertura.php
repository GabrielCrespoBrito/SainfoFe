<?php

namespace App\Jobs\Reporte;

use App\Local;
use App\Marca;
use App\Venta;
use App\Vendedor;
use App\ClienteProveedor;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class ReporteVendedorCobertura
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
  protected $marcaId;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($vendedor, $local, $fecha_desde, $fecha_hasta, $cliente, $marcaId = null)
  {
    $this->vendedor = $vendedor;
    $this->local = $local;
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->cliente = $cliente;
    $this->marcaId = $marcaId;
    $this->handle();
  }

  /**
   * Execute the job.
   *
   */
  public function getQuery()
  {
    $marcaId = $this->marcaId;

    $query = Venta::with([
      'cliente_with' => function ($query) {
        $query
        ->where('TipCodi', 'C')
        ->withoutGlobalScopes();
      },
      'items.producto',
      'vendedor' => function ($query) {
        $query->withoutGlobalScopes();
      }])
      ->whereBetween('VtaFvta', [$this->fecha_desde, $this->fecha_hasta])
      ->whereIn('VtaFMail', [StatusCode::CODE_0001, StatusCode::CODE_0011])
      ->whereIn('TidCodi', [Venta::BOLETA, Venta::FACTURA, Venta::NOTA_DEBITO,  Venta::NOTA_CREDITO, Venta::NOTA_VENTA])
      ->when($marcaId, function ($query) use ($marcaId) {
        $query->whereHas('items.producto', function ($query) use ($marcaId) {
          $query->where('marcodi', $marcaId);
        });
      })
      ->when($this->local, function ($query) {
        $query->where('LocCodi', $this->local);
      })
      ->when($this->cliente, function ($query) {
        $query->where('PCCodi', $this->cliente);
      })
      ->when($this->vendedor, function ($query) {
        $query->where('Vencodi', $this->vendedor);
      });


    // if ($this->local) {
    //   $query->where('LocCodi', $this->local);
    // }

    // if ($this->cliente) {
    //   $query->where('PCCodi', $this->cliente);
    // }

    // if ($this->vendedor) {
    //   $query->where('Vencodi', $this->vendedor);
    // }

    // dd($query->get());

    return
      $query
      ->get()
      ->groupBy(['Vencodi', 'PCCodi']);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();

    // dd( $query );
    // _dd($query->first()->first() );
    // exit();

    // Si no existen registros, deter el resto del script
    if ($query->count() === 0) {
      return;
    }

    $data = [];
    $this->addToData($data, $this->getInfoReporte());
    // dd($data);;

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
    foreach ($vendedores_group as $vendedor_id => $coberturas_vendedor) {


      $this->current_fecha = $vendedor_id;
      $data['items'][$vendedor_id] = [];
      $add = &$data['items'][$vendedor_id];
      // $vendedor = $coberturas_vendedor->first()->vendedor; 
      $vendedor = Vendedor::withoutGlobalScopes()->where('Vencodi', $vendedor_id)->first();
      $vendedor_info = [
        'id' => $vendedor_id,
        'nombre' => $vendedor->vennomb,
        'nombre_complete' =>
        sprintf( "%s %s", $vendedor->vennomb, $vendedor->ventel1),
      ];

      $this->addToData($add, $vendedor_info);
      $total_vendedor = &$add['total'];
      $total_vendedor['total_coberturas'] = count($coberturas_vendedor);
      $total_reporte['total_coberturas'] += count($coberturas_vendedor); 


      $this->processCoberturas($coberturas_vendedor, $add, $data, $total_reporte, $total_vendedor);
    }
  }

  /**
   * Procesar el dia de la venta
   * 
   * @return void
   */
  public function processCoberturas($coberturas_vendedor, &$addToAdd, &$data, &$total_reporte, &$total_vendedor)
  {
    // dd( func_get_args());

    // 
    foreach ($coberturas_vendedor as $cobertura_id => $ventas) {

      $add = &$addToAdd['items'][$cobertura_id];
      $this->addToData($add,  $this->getInfoCobertura($ventas->first()->cliente_with), false);
      $total_cobertura = &$add['total'];

      foreach ($ventas as $venta) {

        if( $this->marcaId ){

          foreach( $venta->items as $item ){
            $data_utilidad = (object) $item->dataUtilidad();
            $this->addToTotal($total_reporte, $data_utilidad);
            $this->addToTotal($total_vendedor, $data_utilidad);
            $this->addToTotal($total_cobertura, $data_utilidad);
          }
        }

        else {
          $data_utilidad = (object) $venta->getTotalesPago();
          $this->addToTotal($total_reporte, $data_utilidad);
          $this->addToTotal($total_vendedor, $data_utilidad);
          $this->addToTotal($total_cobertura, $data_utilidad);
        }
      }
    }
  }

  public function addToTotal(&$total, $data_utilidad)
  {
    $total['importe'] += $data_utilidad->importe;
    $total['cantidad'] += $data_utilidad->cantidad;
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
      'total_coberturas' => 0,
      'cantidad' => 0,
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
    $marca = $this->marcaId ? Marca::find($this->marcaId)->MarNomb : 'TODOS';

    return [
      'reporte_nombre' => self::REPORTE_NOMBRE,
      'empresa_nombre' => $empresa->nombre(),
      'empresa_ruc' => $empresa->ruc(),
      'marcaId' => $this->marcaId,
      'marca' => $marca,
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
  public function getInfoCobertura($cliente)
  {
    return [
      'cliente_codigo' => $cliente->PCCodi,
      'cliente' => $cliente->PCNomb,
      'cliente_ruc' => $cliente->PCRucc,
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
