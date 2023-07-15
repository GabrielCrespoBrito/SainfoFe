<?php

namespace App\Jobs\Venta;

use App\Venta;
use App\VentaCredito;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateFormaPagoDiario
{
	use Dispatchable;

	public $items;
	public $venta;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct( Venta $venta, $items)
	{
		$this->items = $items;
		$this->venta = $venta;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
    $fp = $this->venta->forma_pago;
    $dias = $fp->dias;

    $items = $this->items;

    if( $dias->count() == 1 ){

      $dia = $dias->first();
      $fecha_pago = new Carbon($this->venta->VtaFvta);

      $fecha_pago
      ->addDays( $dia->PgoDias )
      ->format( 'Y-m-d' );

			$monto = $this->venta->VtaImpo;

			if( $this->venta->hasMontoRetencion() ){
				$monto -= (float) $this->venta->totales_documento->retencion;
			}
      
      if ($this->venta->hasDetraccion()) {
        $monto -= (float) $this->venta->totales_documento->detraccion;
      }

      $items = [
        [
          'monto' => fixedValue($monto),
          'fecha_pago' => $fecha_pago,
          'PgoCodi' => $dia->PgoCodi,
        ]
      ];
    }

		// fecha_pago
		$index = 0;
		foreach( $items as $item ){
			VentaCredito::create([
				'item' => agregar_ceros($index,2,1),
				'VtaOper' => $this->venta->VtaOper,
				'monto' => $item['monto'],
				'fecha_pago' => $item['fecha_pago'],
				'forma_pago_id' => $item['PgoCodi'],
        'MonCodi' => $this->venta->MonCodi,
				]);
				$index++;
		}
	}
}
