<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidRecaptcha;

class BusquedaDocumentoRequest extends FormRequest
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
		return  [
			'ruc' => 'required|max:11',
			'tipo_documento' => 'required',
			'serie' => 'required',
			'numero' => 'required',
			'g-recaptcha-response' => ['required', new ValidRecaptcha()]
		];
	}

	public function messages()
	{
		return [
			'g-recaptcha-response.required' => 'Es requerido el llenar el captcha'
		];
	}
}
