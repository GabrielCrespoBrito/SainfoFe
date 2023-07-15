<?php

namespace App\Http\Requests\Guia;

use App\M;
use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class StoreSimply extends FormRequest
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
			"tipo" => 'required|in:1,2',
			"orden_compra" => "sometimes|nullable|max:50",
			"fecha_emision" => "date",
			"observacion" => "sometimes|nullable|max:100",
			'items.*.id' => 'required',
			'items.*.cantidad' => 'required|numeric|min:0.1',
		];
  }



	public function withValidator($validator)
	{
		if (!$validator->fails()) {
			$validator->after(function ($validator) {
        
				$venta = Venta::with('items')->findOrfail($this->route()->parameters['id']);
        $cliente = $venta->cliente;

        // Validar el tipo de cliente de la guia
        if( $this->tipo == "2" ){
          if (!$cliente->isRucOrDni()) {
            $validator->errors()->add('cliente_documento', __('validation.guia_remision_cliente') );
            return ;
          }
        }

				if( $venta->guias_ventas->count() ){
					$validator->errors()->add('id', 'Ha este documento no se pueden crear guias ya que tiene tiene asociadas guias');
					return false;
				}

				$venta_items = $venta->items;
				
				foreach ( $this->items as $item) {
					$venta_item = $venta_items->where('Linea', $item['id'])->first();

					# Validar id correcto
					if(! $venta_item){
						$validator->errors()->add('id', 'Hay algun item incorrecto');
						return;
					}

					# Validated
					if( $item['cantidad'] > $venta_item->DetSdCa ){
						$message = "La cantidad {$item['cantidad']} ingresa del producto {$venta_item->DetNomb} excede la por enviar del producto {$venta_item}";
						$validator->errors()->add( 'id', $message );
						return;
					}

				}
			});
		}
	}
}
