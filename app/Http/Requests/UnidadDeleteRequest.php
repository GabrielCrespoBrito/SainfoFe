<?php

namespace App\Http\Requests;

use App\Unidad;
use Illuminate\Foundation\Http\FormRequest;

class UnidadDeleteRequest extends FormRequest
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

	public function withValidator($validator){

		if( !$validator->fails() ){

			$validator->after(function($validator){

				$unidad = Unidad::find($this->route()->parameters()["id_unidad"] );

				if( substr($unidad->Unicodi,-2) == "01"){
					notificacion('Accion no autorizada', "No se puede eliminar la unidad por defecto", 'error');
					$validator->errors()->add('error' , 'No se puede borrar la unidad por defecto');
					return;
				}

			});
		}

	}
}
