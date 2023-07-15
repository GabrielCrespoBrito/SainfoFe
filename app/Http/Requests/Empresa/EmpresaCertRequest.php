<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaCertRequest extends FormRequest
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
			'usuario_sol' => 'required|max:100',
			'clave_sol'   => 'required|max:100',
			'cert_password' => 'required|max:100',
			'cert_key' => 'required|file',
			'cert_cer' => 'required|file',
			'cert_pfx' => 'required|file',
		];
	}

	public function withValidator($validator)
	{
		$validator->after(function ($validator) {

			if( !$validator->fails() ){
				
				if ($this->cert_key->getClientOriginalExtension() != 'key') {
					$validator->errors()->add('cert_key', 'La extension del archivo tiene que ser .key');
				}
				
				if ($this->cert_cer->getClientOriginalExtension() != 'cer') {
					$validator->errors()->add('cert_cer', 'La extension del archivo tiene que ser .cer');
				}
				if ($this->cert_pfx->getClientOriginalExtension() != 'pfx') {
					$validator->errors()->add('cert_pfx', 'La extension del archivo tiene que ser .pfx');
				}
			}

		});
	}

}
