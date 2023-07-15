<?php

namespace App\Http\Requests\Empresa;

use App\Empresa;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
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
			'password' => 'required'
		];
	}

	public function withValidator($validator)
	{
		if (!$validator->fails()) {
			$validator->after(function($validator){

				$empresa_id = $this->route()->parameters()['id'];
				$empresa = Empresa::find($empresa_id);

				if( $empresa->isActive() ){
					$validator->errors()->add( 'id' , 'La empresas activas no puede eliminarse' );
					return;
				}


				if( config('app.password_delete_empresa') !== $this->password ){
					$validator->errors()->add( 'id' , 'La contraseña es incorrecta' );
					return;
				}

			});
		}
	}
}
