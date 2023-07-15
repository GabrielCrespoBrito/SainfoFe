<?php

namespace App\Jobs\Empresa;

use Illuminate\Foundation\Bus\Dispatchable;

class DeleteAllForFailCreation
{
	use Dispatchable;

	// #
	public $empresa;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($empresa)
	{
		$this->empresa = $empresa;
	}

	/**
	 * Eliminar toda la información de la empresa, opciones, usuaro_documento, usuario_empresa, usuario_local, ademas de registro hostname y website y base de datos
	 *
	 * @return void
	 */
	public function handle()
	{
		$empresa = $this->getEmpresa();
		if( $empresa == null ){
			return;
		}
    
		$empresa->deleteAllInformation();
	}

	/**
	 * Get the value of empresa
	 */
	public function getEmpresa()
	{
		return $this->empresa;
	}

	/**
	 * Set the value of empresa
	 *
	 * @return  self
	 */
	public function setEmpresa($empresa)
	{
		$this->empresa = $empresa;

		return $this;
	}
}