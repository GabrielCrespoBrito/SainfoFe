<?php

namespace App\Jobs;

use App\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReservarProducto implements ShouldQueue
{
	use Dispatchable;

	public $preventa;

	public function __construct( $preventa )
	{
		$this->preventa = $preventa;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->preventa;
		foreach ( $this->preventa->items as $item ) {
			$quantity = $item->unidad->getRealQuantity( $item->DetCant );
			$item->producto->makeReserved( $quantity );
		}
	}
}
