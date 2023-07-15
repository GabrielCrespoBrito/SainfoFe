<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SavePhoneRequest extends FormRequest
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
		$pattern = "/^(?=(.{9})$)(924|932|930)\d+$/";
		return [
			'phone' => 'bail|required|digits:9|unique:usuarios,usutele'
		];
	}


	public function messages()
	{
		return [
			'phone.unique' => 'El número ingresado ya se encuentrar en uso',
		];
	}


	public function withValidator($validator)
	{
		if( !$validator->fails() ){
			$validator->after(function($validator){
				if( auth()->user()->hasPhoneNumber() ){
					$validator->errors()->add('phone' , 'Este usuario tiene registrado un celular');
				}
			});
		}
	}

}
