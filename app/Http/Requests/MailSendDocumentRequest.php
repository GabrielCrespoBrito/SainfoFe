<?php

namespace App\Http\Requests;

use App\Venta;
use Illuminate\Foundation\Http\FormRequest;

class MailSendDocumentRequest extends FormRequest
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
		   // 'id_factura' => 'required|exists:ventas_cab,VtaOper',
		   'id_factura' => 'required',
		];
	}

	public function withValidator($validator)
	{
		$venta = Venta::find($this->id_factura);

		$validator->after(function ($validator) use($venta) {

			if( is_null($venta) ){
				$validator->errors()->add('id_factura', 'El documento no existe');
			}

			if( $venta->cliente->PCMail == NULL ){

				$validator->errors()->add('Email', 'El Cliente no tiene un email asociado');
			}
			
		});

	}

}
