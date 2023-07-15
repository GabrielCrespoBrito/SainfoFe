<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaSeleccionadaRequest extends FormRequest
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

	public function rules()
	{
		return [
			'empresa' => 'required',
			'periodo' => 'required|digits:4',
		];
	}

	public function withValidator($validator)
	{
		if (!$validator->fails()) {

			$validator->after(function ($validator) {

				$userEmpresas = auth()->user()->empresas;
				$userEmpresa = $userEmpresas->where('empcodi', $this->empresa)->first();

				# Validar que la empresa a la que intenta acceder sea una a la que este asociada el usuario
				if ($userEmpresa == null) {
					$validator->errors()->add('empresa', 'La empresa a la que esta intentando acceder no esta asociada a este usuario');
					return;
				}

				# Validar que el periodo sea un periodo real de la empresa
				if ($userEmpresa->empresa->periodos->where('Pan_cAnio', $this->periodo)->first() == null) {
					$validator->errors()->add('empresa', 'El año de trabajo es incorrecto');
					return;
				}

			});

		}
	}
}
