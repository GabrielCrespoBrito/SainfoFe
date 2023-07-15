<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
		// last_password
		return [
			'last_password' => 'required',
			'password' => 'required|min:8|confirmed',
		];
	}

	public function withValidator($validator)
	{


		if( !$validator->fails() ) {
			$validator->after(function($validator){
				if( !auth()->user()->isCorrectPassword($this->last_password) ){
					$validator->errors()->add('last_password' , 'La contraseña actual es incorrecta');
					return;
				}
			});
		}
	}
}
