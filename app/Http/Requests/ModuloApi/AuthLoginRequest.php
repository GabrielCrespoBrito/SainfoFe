<?php

namespace App\Http\Requests\ModuloApi;

use App\ModuloApi\User;
use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
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
			'username' => 'required',
			'password' => 'required',
		];
	}

	public function withValidator($validator)
	{
		if (!$validator->fails()) {

			$validator->after(function ($validator) {

				$user = User::where('username', $this->username)->where('password', $this->password)->first();
				if (is_null($user)) {
					return $validator->errors()->add('username', 'Usuario o contraseña incorrectos');
				}
			});

		}
	}
}
