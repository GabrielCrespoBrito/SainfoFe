<?php

namespace App\Http\Requests\CompraPago;

use App\Caja;
use App\Compra;
use App\Models\Compra\CompraPago;
use Illuminate\Foundation\Http\FormRequest;

class CompraPagoDeleteRequest extends FormRequest
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
				$pago = CompraPago::findOrfail($this->id_pago);
				// $caja = $pago->caja;
				$caja = Caja::currentCaja();
				if ( ! $caja ) {
					$validator->errors()->add('tipo', "Tiene que haber una caja aperturada,  por lo tanto no se puede eliminar ni modificar un pago");
				}
			});
		} 
	}
}
