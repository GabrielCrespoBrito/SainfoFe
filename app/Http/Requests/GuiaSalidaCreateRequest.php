<?php

namespace App\Http\Requests;

use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class GuiaSalidaCreateRequest extends FormRequest
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
		'id_almacen' => "required",
		'id_movimiento' => "required",
		'is_electronica' => "sometimes|in:0,1",
		];		
	}

	public function withValidator($validator)
	{
		$venta = Venta::find($this->id_factura);
		
		$validator->after(function ($validator) use ($venta) {
			if( $venta->guia ){
				$validator->errors()->add('id_factura', 'Este documento ya tiene una guia asociada');			}
		});
	}

	public function messages()
	{
		return [
			'id_factura.unique' => 'Esta compra ya tiene una guia de salida',
			'id_almacen.required' => 'El campo de almacen es necesario',			
		];
	}

}
