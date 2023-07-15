<?php

namespace App\Http\Requests;

use App\Http\Controllers\ClienteAdministracion\ClienteDashboardController;
use Illuminate\Foundation\Http\FormRequest;

class ClienteDashboardUpdatePasswordRequest extends FormRequest
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
			'last_password'  => 'required',
			'password'  => 'required|alpha_num',
			'password_confirmation'  => 'required|same:password',
		];
	}

	public function withValidator($validator){


		if( !$validator->fails() ){

			$validator->after(function($validator){

				$cliente = get_cliente();

				if( $cliente->PCDocu != $this->last_password  ){
					$validator->errors()->add( 'last_password' , 'La contraseña es anterior es incorrecta' );					
				}

			});

		}

	}


}
