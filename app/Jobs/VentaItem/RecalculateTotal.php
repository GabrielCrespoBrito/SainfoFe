<?php

namespace App\Jobs\VentaItem;

use App\Unidad;
use App\VentaItem;
use Illuminate\Support\Facades\Log;
use App\Models\Venta\Traits\Calculator;
use Illuminate\Foundation\Bus\Dispatchable;

class RecalculateTotal
{
	use Dispatchable;


	public $item;
	public $calculatePrecio;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(VentaItem $item, bool $calculatePrecio = true)
	{
		$this->item = $item;
		$this->calculatePrecio = $calculatePrecio;
	}



	public function getPrecio($item)
	{
		return $item->DetImpo / $item->DetCant;
	}


	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		session('empcodi', $this->item->EmpCodi);

		$item = $this->item;
		$venta = $item->venta;
		$unidad = Unidad::withoutGlobalScopes()->where('Unicodi', $item->UniCodi)->first();
		$precio = $this->calculatePrecio ? $this->getPrecio($item) : $item->DetPrec;
		$tc = $venta->VtaTcam;
		$isSol = $venta->isSol();
		$calculator = new Calculator();
		$calculator->setValues(
			$precio,
			$item->DetCant,
			(bool) $item->incluye_igv,
			$item->DetBase,
			$item->DetDcto,
			false, // bolsa
			0, // isc
			$unidad->getFactor(), // factor
			$tc,
			$isSol,
			$unidad->UniPeso
		);

		$calculator->calculate();
		$totales = $calculator->getCalculos();
		
		$item->DetIGVP = $totales['igv_total'];
		$item->DetPrec = $precio;
		$item->lote = $totales;
		$item->save();
	}
}
