<?php

namespace App\Http\Requests\Guia;

use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class GuiaIngresoStoreRequest extends FormRequest
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
			'GuiSeri' => 'required|max:4',
			'GuiNumee' => 'required|max:6',
			'items.*.DetCant' => 'required',
      'items.*.DetCant' => 'required',
      'cliente_documento' => 'required'
		];
	}

	// with validator

	public function withValidator($validator)
	{

		if( ! $validator->fails() ){

			$validator->after(function ($validator) {
        
				if ( $this->doc_ref ) {

					$venta = Venta::where([['VtaNume', $this->doc_ref], ['EmpCodi', empcodi()]])->first();
					$items = collect($this->items);

					foreach ($items as $item) {

						if (!is_numeric($item['DetCant'])) {
							$validator->errors()->add('items', 'La cantidad del produncto a enviar tiene que ser númerico');
							return;
						} elseif ($item['DetCant'] < 0) {
							$validator->errors()->add('items', 'La cantidad del produncto tiene que ser mayor que 0');
							return;
						}
					}
	
					$venta_items = $venta->items;
					$error = false;

					foreach ($items as $item) {

						$venta_item = $venta->items
							->where('DetCodi', $item['DetCodi'])
							->where('UniCodi', $item['UniCodi'])
							->first();

						$guia_items = collect($this->items);

						if (is_null($venta_item)) {
							$error = true;
							$validator->errors()->add('items', 'No se puede cambiar la información de los productos, que no sea la cantidad');
						}
					}



					if (!$error) {

						if ($items->sum('DetCant') > $venta->VtaSdCa) {

							$validator->errors()->add('items', 'No puede registrar mas cantidad de los productos que la que existe en el documento de referencia');
						}

						foreach ($items as $item) {

							$venta_item = $venta_items
								->where('DetCodi', $item['DetCodi'])
								->where('UniCodi', $item['UniCodi'])
								->first();

							if ($item['DetCant'] > $venta_item->DetCant) {

								$validator->errors()->add('items', 'No puede haber mas cantidad del producto, que en el documento de referencia');
							}
						}
					}
				}
			}); // after

		}
	}


	public function messages()
	{
		return 
    [
      'doc_ref.required' =>    'El documento de referencia es obligatorio',
      'GuiSeri.max' => 'La serie de la guia no puede ser de mas de :max caracteres',
      'GuiNumee.max' => 'La numeración de la guia no puede ser de mas de :max caracteres',
      'items.*.DetCant' => 'required',
		];
	}
}
