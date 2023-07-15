<?php

namespace App\Jobs\Resumen;

use App\Resumen;

/**
 * Clase para enviar a la sunat el resumen y devoler un message
 */
class EnviarValidarSunat
{
	protected $response;
	protected $resumen;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Resumen $resumen)
	{
		$this->resumen = $resumen;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function process()
	{
		$this->resumen->enviarSunat();

		if ($this->resumen->hasTicket()) {
			$this->resumen->validateTicket();
		}
		
	}


	public function getResponse()
	{
		return $this->response;
	}
}
