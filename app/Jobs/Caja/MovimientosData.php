<?php

namespace App\Jobs\Caja;

use App\Caja;
use App\Moneda;
use App\Control;
use App\TipoPago;
use App\TipoCambioPrincipal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovimientosData
{

  public $tipo_cambio = null;
  public $caja;
  public $total_venta = 0;
  public $pago_cobranza = 0;
  public $saldo_apertura = 0;
  public $ingresos = 0;
  public $salidas = 0;
  public $pago_efectivo = 0;
  public $saldo = 0;
  public $metodos_pagos = [];


  public function getOrGenerateTipoCambio()
  {
    if ($this->tipo_cambio) {
      return $this->tipo_cambio;
    }

    return $this->tipo_cambio = TipoCambioPrincipal::ultimo_cambio(false);
  }

  public function __construct(Caja $caja)
  {
    $this->caja = $caja;
    $this->setMetodoPagos();
  }

  public function setMetodoPagos()
  {
    $metodos_pagos = TipoPago::get()->pluck('TpgNomb', 'TpgCodi')->toArray();

    foreach ($metodos_pagos as $metodoId => $nombre) {

      $this->metodos_pagos[$metodoId] = [
        'nombre' => $nombre,
        'total' => 0,
      ];
    }
  }

  public function loopVentas()
  {
    $docs = DB::connection('tenant')->table('ventas_cab')
      ->where('ventas_cab.CajNume', '=', $this->caja->CajNume)
      ->whereIn('ventas_cab.TidCodi', ['01', '03', '08', '09', '52'])
      ->select(
        'ventas_cab.VtaImpo as total',
        'ventas_cab.VtaTcam as tipo_cambio',
        'ventas_cab.MonCodi as moneda'
      )->get();

    foreach ($docs as $doc) {
      $this->total_venta += $this->getMontoEnSoles($doc->total, $doc->moneda, $this->getTipoCambioMov($doc));
    }
  }

  public function getMontoEnSoles($monto, $moneda, $tipoCambio)
  {
    return $moneda == Moneda::SOL_ID ? $monto : $monto * $tipoCambio;
  }

  public function getQueryMov($caja = true)
  {
    $movs = DB::connection('tenant')
      ->table('caja_detalle')
      ->leftJoin('ventas_pago',  'ventas_pago.PagOper', '=', 'caja_detalle.DocNume');

    if ($caja) {
      $movs->where('caja_detalle.CajNume', '=', $this->caja->CajNume);
    }

    else {
      $movs
        ->where('caja_detalle.MocFech', '=', $this->caja->CajFech)
        ->where('caja_detalle.LocCodi', '=', $this->caja->LocCodi)
        ->whereNotIn('caja_detalle.CueCodi', [Caja::TIPOCAJA]);
    }

    return $movs
      ->select(
        'caja_detalle.Id as id',
        'caja_detalle.CANINGS as ingreso_soles',
        'caja_detalle.CANINGD as ingreso_dolares',
        'caja_detalle.CANEGRS as egreso_soles',
        'caja_detalle.CANEGRD as egreso_dolares',
        'caja_detalle.TIPCAMB as tipo_cambio',
        'caja_detalle.MonCodi as moneda',
        'caja_detalle.ANULADO as tipo_movimiento',
        'caja_detalle.CtoCodi as clase_movimiento',
        'caja_detalle.MOTIVO as motivo',
        'ventas_pago.TpgCodi as tipo_pago'
      )->get();
  }


  public function loopMovimientos()
  {
    $movs = $this->getQueryMov();

    foreach ($movs as $movimiento) {
      $this->processMovimiento($movimiento);
    }
  }

  public function loopMovimientosBancos()
  {
    $movs = $this->getQueryMov(false);

    foreach ($movs as $movimiento) {
      $this->processMovimiento($movimiento, false);
    }
  }

  public function getTipoCambioMov($movimiento)
  {
    return $movimiento->tipo_cambio ?? $this->getOrGenerateTipoCambio();
  }

  public function getMontoMovimiento($movimiento, $conTipoPago = false)
  {
    if ($movimiento->tipo_movimiento == Caja::INGRESO) {
      $monto = (float) $movimiento->ingreso_soles;
      $moneda = Moneda::SOL_ID;

      if (!$monto) {
        $monto = (float) $movimiento->ingreso_dolares;
        $moneda = Moneda::DOLAR_ID;
      }
    }
    
    else {
      $monto = (float) $movimiento->egreso_soles;
      $moneda = Moneda::SOL_ID;

      if (!$monto) {
        $monto = (float) $movimiento->egreso_dolares;
        $moneda = Moneda::DOLAR_ID;
      }
    }

    $monto = $this->getMontoEnSoles($monto, $moneda, $this->getTipoCambioMov($movimiento));

    if ($conTipoPago && $movimiento->tipo_pago) {
      $this->processIngresoConTipoPago($monto, $movimiento->tipo_pago);
    }

    return $monto;
  }

  public function processIngresoConTipoPago($monto, $tipoPago)
  {
    $this->metodos_pagos[$tipoPago]['total'] += $monto;
  }

  public function processMovimiento($movimiento, $caja = true)
  {
    # Ingresos 

    if ($movimiento->tipo_movimiento == Caja::INGRESO) {

      # Apertura
      if ($movimiento->clase_movimiento == Control::CAJA) {
        $this->saldo_apertura =
          $movimiento->ingreso_soles + ($movimiento->ingreso_dolares * $this->getTipoCambioMov($movimiento));
      }     

      else if ($movimiento->clase_movimiento == Control::INGRESO_VENTA) {

        $monto = $this->getMontoMovimiento($movimiento, true);

        if( $caja ){

          if( strpos(strtolower($movimiento->motivo),'cobranza') !== false ){
            $this->pago_cobranza += $monto;
          }

          $this->pago_efectivo += $monto;
        }

      }
      
      else if ($movimiento->clase_movimiento == Control::OTROS_INGRESOS) {
        $this->ingresos += $this->getMontoMovimiento($movimiento);
      }
    }

    # Egresos

    else {
      $this->salidas += $this->getMontoMovimiento($movimiento, true);
    }
  }

  public function calculateSaldo()
  {
    $this->saldo = $this->saldo_apertura + $this->ingresos + $this->pago_efectivo - $this->salidas;
  }

  public function processMetodoPagos()
  {
    $metodos_pagos = [];

    foreach ($this->metodos_pagos as $id => $metodo_pago) {
      if ($metodo_pago['total'] != 0) {
        $metodos_pagos[$id] = $metodo_pago;
      }
    }

    $this->metodos_pagos = $metodos_pagos;
  }

  public function handle()
  {
    $this->loopVentas();
    $this->loopMovimientos();
    $this->loopMovimientosBancos();
    $this->calculateSaldo();
    $this->processMetodoPagos();

    return (object) [
      'saldo_apertura' => $this->saldo_apertura,
      'ingresos' => $this->ingresos,
      'salidas' => $this->salidas,
      'total_ventas' => $this->total_venta,
      'pago_efectivo' => $this->pago_efectivo,
      'pago_cobranza' => $this->pago_cobranza,
      'saldo' => $this->saldo,
      'metodos_pagos' => $this->metodos_pagos,
    ];
  }
}
