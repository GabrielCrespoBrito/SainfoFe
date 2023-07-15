<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubirCert extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'cert_key' => 'required|file',
			'cert_cer' => 'required|file',
			'cert_pfx' => 'required|file',
		];
	}

	public function withValidator($validator)
	{

		if (!$validator->fails()) {

			$validator->after(function ($validator) {

				# Validar los certificados
				if (!$this->cert_cer && !$this->cert_key && !$this->cert_pfx) {
					$validator->errors()->add('cert_cer', 'Tiene que subir al menos un certificado');
					$validator->errors()->add('cert_key', 'Tiene que subir al menos un certificado');
					$validator->errors()->add('cert_pfx', 'Tiene que subir al menos un certificado');
				} 
				
				else {

					if ($this->cert_key) {
						if ($this->cert_key->getClientOriginalExtension() != 'key') {
							$validator->errors()->add('cert_key', 'La extension del archivo tiene que ser .key');
						}
					}

					if ($this->cert_cer) {
						if ($this->cert_cer->getClientOriginalExtension() != 'cer') {
							$validator->errors()->add('cert_cer', 'La extension del archivo tiene que ser .cer');
						}
					}
					if ($this->cert_pfx) {
						if ($this->cert_pfx->getClientOriginalExtension() != 'pfx') {
							$validator->errors()->add('cert_pfx', 'La extension del archivo tiene que ser .pfx');
						}
					}
				}
			});
		}
	}
}
