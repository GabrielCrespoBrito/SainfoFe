<?php

namespace App\Jobs\Reporte;

use App\Moneda;
use App\Venta;
use Illuminate\Support\Facades\DB;

class MejoresClientesReportData
{
  public $data;
  public $fecha_desde;
  public $fecha_hasta;
  public $local;


  public function getData()
  {
    return $this->data;
  }

  public function setData($data)
  {
    $this->data = $data;
  }

  public function __construct($fecha_desde, $fecha_hasta, $local)
  {
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->local = $local;

    $this->handle();
  }

  public function getQuery()
  {
    $clientes =
    DB::connection('tenant')->table('prov_clientes')    
    ->join('ventas_cab', 'ventas_cab.PCCodi', '=', 'prov_clientes.PCCodi')
    ->whereBetween('ventas_cab.VtaFvta', [ $this->fecha_desde , $this->fecha_hasta ])
    ->where('ventas_cab.VtaEsta', '!=', 'A' );

    if ($this->local) {
      $clientes->where('ventas_cab.LocCodi', $this->local);
    }

    return $clientes
    ->select(
      'prov_clientes.PCCodi as codigo',
      'prov_clientes.PCNomb as nombre',
      'prov_clientes.PCRucc as documento',
      'ventas_cab.VtaImpo as importe',
      'ventas_cab.MonCodi as moneda',
      'ventas_cab.VtaTCam as tipo_cambio',
    )
    ->get()
    ->groupBy(['codigo']);
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $clientes_groups = $this->getQuery();

    $data = [];

    foreach( $clientes_groups as $codigo_cliente => $ventas_cliente ){
      
      $venta_cliente_first = $ventas_cliente->first();

      $data_cliente = [
        'codigo' => $venta_cliente_first->codigo,
        'nombre' => $venta_cliente_first->nombre,
        'documento' => $venta_cliente_first->documento,
        'importe' => 0,
        'cantidad' => $ventas_cliente->count(),
        // 'cantidad' => 0,
      ];

      foreach( $ventas_cliente as $venta ){

        if( $venta->moneda ==  Moneda::SOL_ID ){
          $data_cliente['importe'] += $venta->importe;
        }
        else {
          $data_cliente['importe'] += $venta->importe * $venta->tipo_cambio;
        }
      }

      $data[] = $data_cliente;
    }

    $this->setData(collect($data)->sortByDesc('importe'));
  }
}
