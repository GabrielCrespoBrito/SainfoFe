<?php

namespace App\Rules\ModuloApi;

use App\ModuloApi\Models\Empresa\Empresa;
use Illuminate\Contracts\Validation\Rule;

class RucSearchDocument implements Rule
{
	public $message = 'The validation error message.';

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	public function existsEmpresa()
	{
		if (is_null($this->getEmpresa())) {
			$this->setMessage('Esta empresa no se encuentra registrada actualmente en nuestro listado de empresas para consultas');
			return false;
		}

		return true;
	}

	public function isValidEmpresa()
	{
			if (!$this->getEmpresa()->isValidEmpresa() ){
			$nombre  = $this->getEmpresa()->razon_social;
			$this->setMessage("La empresa {$nombre} no tiene consultas disponibles");
			return false;

		}

		return true;
	}

	public function setEmpresa($empresa)
	{
		$this->empresa = $empresa;
	}

	public function getEmpresa()
	{
		return $this->empresa;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$this->setEmpresa(Empresa::findByRuc($value));
		
		if ( ! $this->existsEmpresa()) {
			return false;
		}

		if (!$this->isValidEmpresa()) {
			return false;
		}

		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function message()
	{
		return $this->message;
	}
}
