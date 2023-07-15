<?php

namespace App\Http\Requests\Guia;

use App\GuiaSalida;
use Illuminate\Foundation\Http\FormRequest;

class GuiaIngresoUpdateRequest extends FormRequest
{
	/**
  * Determine if the user is authorized to make this request.
  *
  * @return bool
  */
	public function authorize()
	{
		return true;
	}

	/**
  * Get the validation rules that apply to the request.
  *
  * @return array
  */
	public function rules()
	{
		return [
			'items' => 'required'
		];
	}

	public function withValidator($validator) 
	{
		$validator->after(function ($validator) {

			// $id_guia = $this->route()->parameters()['id'];
			// $guia = GuiaSalida::find($id_guia);
			// $venta = $guia->venta;
			// $items = collect($this->items);

			// $venta_items = $venta->items;
			// $error = false;

			// foreach ($items as $item) {

				// $venta_item = $venta->items
				// 	->where('DetCodi', $item['DetCodi'])
				// 	->where('UniCodi', $item['UniCodi'])
				// 	->first();

				// $guia_item = $guia->items
				// 	->where('DetItem', $item['DetItem'])
				// 	->where('Linea', $item['Linea'])
				// 	->where('UniCodi', $item['UniCodi'])
				// 	->where('DetCodi', $item['DetCodi'])
				// 	->first();

				// if (is_null($guia_item)) {
				// 	$error = true;
				// 	$validator->errors()->add('items', 'No se puede cambiar la información de los productos, que no sea la cantidad');
				// }
			// }

			// if (!$error) {

				// if ($items->sum('DetCant') > $venta->VtaSdCa) {
				// 	$validator->errors()->add('items', 'No puede registrar mas cantidad de los productos que la que existe en el documento de referencia');
				// }

				// foreach ($items as $item) {

				// 	$venta_item = $venta_items
				// 		->where('DetCodi', $item['DetCodi'])
				// 		->where('UniCodi', $item['UniCodi'])
				// 		->first();

				// 	$guia_item = $guia->items
				// 		->where('DetItem', $item['DetItem'])
				// 		->where('Linea', $item['Linea'])
				// 		->where('UniCodi', $item['UniCodi'])
				// 		->where('DetCodi', $item['DetCodi'])
				// 		->first();

				// 	if ($item['Detcant'] > $venta_item->DetCant) {

				// 		$validator->errors()->add('items', 'No puede haber mas cantidad del producto, que en el documento de referencia');
				// 	}

				// }

			// }

		});
	}

}