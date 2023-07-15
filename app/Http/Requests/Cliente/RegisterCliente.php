<?php

namespace App\Http\Requests\Cliente;

use App\ClienteProveedor;
use App\Models\Tienda\Cliente;
use App\Rules\DniValidation;
use App\Rules\RucValidation;
use App\TipoDocumento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;
use Sabberworm\CSS\Value\ValueList;

class RegisterCliente extends FormRequest
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
			'value' => 'required'
		];
	}

	public function withValidator($validator)
	{
		// $this->tipo_documento = "ccc";

		if (!$validator->fails()) {
			
			$validator->after(function ($validator) {


				$value = trim($this->value);
				$valueLen = strlen($value);

				// Validator DNI o RUC
				if (is_numeric($value)) {

					// Validar que no existe el cliente ingresado
					if ($valueLen === 8 || $valueLen === 11) {
						if (ClienteProveedor::findByRuc($value) != null) {
							$validator->errors()->add('documento', 'Ya hay un cliente registrado con el documento ingresado');
							return;
						}
					}

					// Es DNI
					if ($valueLen === 8) {
						$this->tipo_documento = TipoDocumento::DNI;
						$dniValidator = new DniValidation(false);
						if (!$dniValidator->passes('value', $value)) {
							$validator->errors()->add('documento', 'No se encuentran datos con el número de DNI solicitado');
						}
						return;
					}

					// Es Ruc
					elseif ($valueLen === 11) {
						$rucValidator = new RucValidation(false);
						$this->tipo_documento = TipoDocumento::RUC;
						if (!$rucValidator->passes('value', $value)) {
							$validator->errors()->add('documento', 'No se encuentran datos con el número de RUC solicitado');
						}
						return;
					} else {
						$validator->errors()->add('documento', 'No número de documento ingresado tiene que tener 8 o 11 digitos');
						return;
					}
				}


				if (!preg_match("/^[a-zA-Z\s]+$/", $value)) {
					$validator->errors()->add('documento', 'El nombre del cliente solo puede contener letras');
				}

				else {
					if (ClienteProveedor::where('PCNomb',$value)->where('TdoCodi',  TipoDocumento::NINGUNA )->first() != null) {
						$validator->errors()->add('documento', 'Ya hay otro cliente registrado con el nombre suministrado');
						return;
					}
				}
				$this->tipo_documento = TipoDocumento::NINGUNA;
			});
		}
	}
}
