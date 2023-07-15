<?php

namespace App\Http\Requests\Resumen;

use App\Resumen;
use Illuminate\Foundation\Http\FormRequest;

class ResumenUpdateTicketRequest extends FormRequest
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
			//
		];
	}

	public function withValidator($validator)
	{
		if (!$validator->fails()) {
			$validator->after(function ($validator) {

				$resumen = Resumen::findMultiple($this->id_resumen, $this->docnume);

				if ( $resumen->hasTicket() == false) {
					$validator->errors()->add('tipo', 'El resumen tiene que tener un ticket para poder actualizar el mismo');
				}

				if ($resumen->hasValidado()) {
					$validator->errors()->add('tipo', 'Ha este resumen ya no se puede actualizar el ticket');
				}
			});
		}
	}
}
