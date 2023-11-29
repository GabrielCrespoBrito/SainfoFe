<?php

namespace App\Http\Requests\VentaPago;

use App\VentaPago;
use Illuminate\Foundation\Http\FormRequest;

class VentaPagoDestroyRequest extends FormRequest
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
			'id_pago' => 'required'
		];
	}

	public function withValidator($validator)
	{
		if( ! $validator->fails() ){

			$validator->after(function ($validator) {

				$pago = VentaPago::findOrfail($this->id_pago);

        $caja = $pago->caja;

				if ( ! $caja->isAperturada() ) {
					$validator->errors()->add('tipo', "La caja ({$caja->CajNume}) donde esta registrada este pago esta cerrada, por lo tanto no se puede eliminar ni modificar");
				}

			});

		} 
	}
}
	