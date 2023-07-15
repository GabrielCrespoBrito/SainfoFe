<?php

namespace App\Jobs\Venta;

use App\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateProductosEnviados 
{
	use Dispatchable;

	public $venta;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Venta $venta)
	{
		$this->venta = $venta;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$venta = $this->venta;
		$items = $venta->items;
		$guias = $venta->guias_relacionadas;

		$guias_items = [];

		// Todos los items en las guias
		foreach( $guias as $guia ){
			foreach( $guia->items as $item  ){
				$guias_items[] = $item;
			}
		}

		$guias_items = collect($guias_items);


		foreach( $items as $item ){
			$porEnviar = 0;
			
			$total_cantidad_enviada =	$guias_items
			->where('DetCodi', $item->DetCodi )
			->where('UniCodi', $item->UniCodi )
			->sum('Detcant');

			
			$porEnviar = $item->DetCant - $total_cantidad_enviada;

			# Actualizar
			$item->update([
				'DetSdCa' => $porEnviar
			]);
		}

		$venta->update([
			'VtaSdCa' => $venta->Vtacant - $guias->sum('guicant')
		]);

	}
}
