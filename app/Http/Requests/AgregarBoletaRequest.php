<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgregarBoletaRequest extends FormRequest
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
			'fecha' => 'date',
			'serie' => 'required',
		];
	}

	public function withValidator($validator)
	{
		if (!$validator->fails()) {

			$validator->after(function($validator){
				// Extra validation
				if ( get_empresa()->sendDirectBoleta()) {
					$validator->errors()->add('boleta', 'Las boletas se envian a la sunat individualmente, no por resumen');
				}
				


			});
			
		}
	}
}
