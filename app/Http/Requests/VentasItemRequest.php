<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentasItemRequest extends FormRequest
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
 		$data = [
			'UniCodi' => 'required',
			'DetUni'  => 'required',
			'DetCant' => 'required|numeric|min:0',                  
			'DetPrec' => 'required|numeric|min:0',
			'DetImpo' => 'required|numeric|min:0',
			'incluye_igv' => 'required|in:0,1'
		];


		if( is_null($this->is_guia)){
			$data['DetDcto'] = 'required|numeric|min:0';			
		}


		return $data;
	}

	public function messages()
	{
		return [
			'DetUni.required'  => 'Es necesario la unidad del producto',			
			'UniCodi.required' => 'El codigo del producto es necesario',            
			'DetDcto.required' => 'El campo de descuento es necesario',
			'detuni.required'  => 'La unidad es requerida',
			'DetPrec.required' => 'El precio del producto es obligatorio',
			'DetPrec.numeric'  => 'El precio del producto tiene que ser numerico',
			'DetPrec.min'      => 'El precio del producto tiene que ser al menos de 0',
			'DetImpo.min'      => 'El importe no puede ser negativo',
		];
	}


	public function withValidator($validator)
	{
		$validator->after(function ($validator){
			
		});

		
	}    

}
