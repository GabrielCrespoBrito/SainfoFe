<?php

namespace App\Jobs\VentaItem;

use App\VentaItem;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RestablecerTipoIgv implements ShouldQueue
{
    use Dispatchable;


	public $item;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(VentaItem $item)
	{
		$this->item = $item;
		//
	}

	// public function validateItems($items, &$validator)
	// {
	//   $calculator = new Calculator();

	//   $index = 0;
	//   foreach ($items as $item) {

	//     $producto = Producto::where('ProCodi', $item['DetCodi'])->first();

	//     if (is_null($producto)) {
	//       $validator->errors()->add('DetCodi', 'El codigo de producto es incorrecto');
	//       return false;
	//     } else {
	//       $unidad = $producto->unidades->where('Unicodi', $item['UniCodi'])->first();
	//       if (is_null($unidad)) {
	//         $validator->errors()->add('UniCodi', "El codigo de la unidad {$item['UniCodi']} del item {$index} es incorrecto cuyo nombre es {$item['DetNomb']}");
	//         return false;
	//       }
	//     }

	//     $isSol = $this->moneda  == Moneda::SOL_ID;
	//     $peso = 0;
	//     // Validar valores 
	//     $base_imponible = strtoupper($item['DetBase']);
	//     $is_bolsa = (bool) $producto->icbper;
	//     $calculator->setValues(
	//       $item['DetPrec'],
	//       $item['DetCant'],
	//       (bool) $item['incluye_igv'],
	//       $base_imponible,
	//       $item['DetDcto'],
	//       $is_bolsa,
	//       $item['DetISP'],
	//       $unidad->getFactor(),
	//       $this->tipo_cambio,
	//       $isSol,
	//       $unidad->UniPeso
	//     );

	//     $calculator->calculate();
	//     $data = $calculator->getCalculos();

	//     if ($base_imponible = !Venta::GRATUITA && $item['DetImpo'] != $data['total']) {
	//       $validator->errors()->add('UniCodi', "El total ({$item['DetImpo']}) suministrado del item ({$index}) no coincide no el total correcto de ({$data['total']})");
	//       return false;
	//     }

	//     $data['producto'] = $producto;
	//     $data['unidad'] = $unidad;
	//     $data['index'] = $index;
	//     $this->totales_items[] = $data;
	//     $index++;
	//   }

	//   return true;
	// }


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
		session( 'empcodi', $this->item->EmpCodi );

		$item = $this->item;
		$venta = $item->venta;
		$unidad = Unidad::withoutGlobalScopes()->where( 'Unicodi', $item->UniCodi)->first();
		$precio = $this->getPrecio($item);

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

			$item->DetPrec = $precio;
			$item->lote = $totales;
			$item->save();
	}
}
