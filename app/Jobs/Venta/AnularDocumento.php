<?php

namespace App\Jobs\Venta;

use App\Resumen;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AnularDocumento
{
	use Dispatchable;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($documento)
	{
		$this->documento = $documento;
	}

	public function sendResumen( $resumen, $onlyValidateTicket = false )
	{
		return $onlyValidateTicket ? $resumen->validateTicket() : $resumen->enviarValidarTicket();
	}

	/**
	 * Despues del envio a la sunat, consulta si el envio fue exitoso, no se envio, o falta validar el ticket en la sunat
	 */
	public function verifiyResumen($resumen)
	{
		// Si esta aceptado
		if ($resumen->DocCEsta == Resumen::ACEPTADO_STATE) {
			return true;
		}

		// Si nisiquiera se envio
		if ($resumen->DocCEsta == Resumen::PENDIENTE_STATE && ! $resumen->hasTicket() ) {
			$resumen->enviarValidarTicket();
		} 
		
		if ($resumen->DocCEsta == Resumen::PROCESANDO_STATE) {
			$resumen->validateTicket();
		}
		
		// return $resumen->Doc
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$documento = $this->documento;
		$detalle = $documento->detalle_anulacion();

		if ($detalle == null) {
			$resumen = $documento->createResumenAnulacion();
			$responde = $this->sendResumen($resumen);
		} 
		else {
			$resumen = $detalle->resumen;
			$responde = $this->sendResumen($resumen,true);
		}

		return $this->validateOrSendSunat( $resumen, $response );


		// Si es nuevo, pero no se ha enviado a la sunat, o esta en estado pendiente-sunat, volver a enviar el ticket
		if ($resumen->DocCEsta == Resumen::PENDIENTE_STATE) {
			$response = $resumen->enviarValidarTicket();
		} 
		
		else if ($resumen->DocCEsta == Resumen::PROCESANDO_STATE) {
			$response =  $resumen->validateTicket();
		}
		

		if ($resumen->DocCEsta == Resumen::ACEPTADO_STATE) {
			$titulo = "Proceso de anulación:";
			$message = $resumen->DocEstado;
			$tipo = $response->success ? "success" : 'error';

			notificacion(
				$titulo,
				$message,
				$tipo,
				['hideAfter' => false]
			);
		} else {
			$documento->searchSunatGetStatus(true);

			if ($documento->isAnulado()) {
				notificacion(
					'Anulación exitosa',
					'El documento ha sido anulado exitosamente',
					'success',
					['hideAfter' => false]
				);
			} 

			else {
			}


			
		}
	}
}
