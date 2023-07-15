<?php

namespace App\Http\Requests\Empresa;

use App\Empresa;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaSaveDefaultInformationDefaultRequest extends FormRequest
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
			$empresa = Empresa::find($id);

			if ( $empresa->empcodi != empcodi() ) {
				$validator->errors()->add('empresa', 'La empresa seleccionada para cargar su información por defecto, tiene que ser la misma, con la que esta trabajando actualmente');
			}

			if( $empresa->hasDefaultInfo() ){
				// $validator->errors()->add('empresa', 'La empresa ya tiene su información cargada');
			}

		});
	}
}
