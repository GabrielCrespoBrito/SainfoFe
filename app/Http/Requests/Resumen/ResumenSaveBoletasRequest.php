<?php

namespace App\Http\Requests\Resumen;

use App\Resumen;
use Illuminate\Foundation\Http\FormRequest;

class ResumenSaveBoletasRequest extends FormRequest
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
			// 'xx' => 'required'
		];
	}

	public function withValidator($validator)
	{
		if ( ! $validator->fails() ) {
			$validator->after(function($validator){

				if (get_empresa()->sendDirectBoleta()) {
					$validator->errors()->add('boleta', 'Las boletas se envian a la sunat individualmente, no por resumen');
				}

				// Extra validation
				if ( $this->id_resumen ) {
					$resumen = Resumen::findMultiple($this->id_resumen, $this->docnume);
					if( $resumen->hasTicket() ){
						$validator->errors()->add('field', 'No se puede modficar las boletas agregadas, despues de que el resumen tiene un ticket');	
					}
					// Extra validation

				}
		});
		}
	}


}
