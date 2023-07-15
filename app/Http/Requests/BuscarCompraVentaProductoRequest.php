<?php

namespace App\Http\Requests;

use App\Producto;
use Illuminate\Foundation\Http\FormRequest;

class BuscarCompraVentaProductoRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'id_producto' => 'required',
		];
	}

	public function withValidator($validator)
	{
		if( !$validator->fails() ){
			if( is_null(Producto::findByProCodi( $this->id_producto)) ){
				$validator->errors()->add('id_producto', 'El producto seleccionado no existe');
				return;
			}
	}

	}


	public function message()
	{
		return [
			'id_producto.required' => 'Hace falta el codigo del producto',						
			'id_producto.exists' => 'Este producto no existe',			
		];
	}
}
