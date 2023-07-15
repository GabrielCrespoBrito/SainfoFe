<?php

namespace App\Http\Requests\Landing;

use App\Rules\RucValidation;
use App\Rules\ValidRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class ContactoFormRequest extends FormRequest
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
			"razon_social" => "required|max:255",
			"ruc" => ["required" , new RucValidation(false) ] ,
			"telefono" => "required",
			"mensaje" => "required|max:255",
			"email" => "required|email",
			// 'g-recaptcha-response' => ['required', new ValidRecaptcha()]
		];
	}

	public function withValidator($validator)
	{
		if( !$validator->fails() ){
			$validator->after(function($validator){

			});
		}
	}

}