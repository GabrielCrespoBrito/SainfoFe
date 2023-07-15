<?php

namespace App\Http\Requests\User;

use App\Rules\RucValidation;
use App\Rules\SolRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClaveSolRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$user = auth()->user();
		
		return 
			$user->isVerificated() && 
			$user->empresas->count() == 0;  
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'ruc' => ['bail', 'required', 'unique:opciones,EmpLin1', 'numeric', 'digits:11', new RucValidation(true)],
			'nombre_comercial' => ['required', 'alpha_num', 'max:255'],
			'direccion' => ['required', 'alpha_num', 'max:255'],
			'email' => ['required', 'email', 'max:100'],
		];
	}

	public function withValidator($validator)
	{
	}

	public function messages()
	{
		return [
			'ruc.unique' => 'El ruc suministrado ya se encuentra en uso',
		];
	}

}
