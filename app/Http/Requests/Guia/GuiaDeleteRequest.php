<?php

namespace App\Http\Requests\Guia;

use App\Models\Guia\Guia;
use Illuminate\Foundation\Http\FormRequest;

class GuiaDeleteRequest extends FormRequest
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
		$validator->after(function ($validator) {

			$id = $this->route()->parameters()['id'];
			$guia = Guia::find($id);

			if ( is_null($guia) ) {
				$msg = "Esta Guia no existe";
				notificacion('Acción invalida',  $msg, 'error');
				$validator->errors()->add('id', $msg );
				return;
			}

			if ($guia->isCerrada()) {
				$msg = "Esta Guia se encuentra cerrado, por lo tanto ya no se puede eliminar";
				notificacion('Acción invalida',  $msg, 'error');
				$validator->errors()->add('id', $msg);
				return;
			}

		});
	}
}
