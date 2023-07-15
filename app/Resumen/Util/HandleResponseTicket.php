<?php

namespace App\Resumen\Util;

use App\Util\Sunat\Request\ResolverWsld;
use Illuminate\Support\Facades\Log;

class HandleResponseTicket extends HandleResponse
{
	/**
	 * Procesar la respuesta
	 */
	public function processResponse()
	{
		if ( $this->clienteCommunicateFails() ) {
			$this->establishClienteError();
			return;
		} 
		
		else if ( $this->serviceCommunicateFails() ) {
			$this->establishCommunicateError();
			return;
		}

		$this->saveValidacionTicket();
	}

	public function saveValidacionTicket()
	{
		$communicate = $this->getCommunicate()['commnucate_data'];
		$status = true;
    $linkResumen = route('boletas.agregar_boleta', $this->resumen->NumOper, $this->resumen->DocNume);
		$statusCode = $code = (int) $communicate->status->statusCode;
		$message = "blank";
		$status = false;

		if( $statusCode == "99" ){
			$message = sprintf('Documento se encuentra CON ERRORES, comuniquese con el proveedor en este caso');
			$this->setMessageCode($message , false);
			$dataCDR = $this->resumen->processCDR($communicate->status->content);
      		$this->resumen->saveError( $statusCode,$dataCDR[0], $dataCDR[1]);
			$status = false;
		}

		else if( $statusCode == "98" ) {
			$message = sprintf('Documento se encuentra EN PROCESO, dirigase al %s e presione el boton de VALIDAR para procesar la petición', $linkResumen);
			$this->resumen->setProcesandoState();
			$status = true;
		} 
		
		else if( $statusCode == "0" ) {
      $dataCDR = $this->resumen->processCDR($communicate->status->content);
			$message = $dataCDR[0];
      $this->resumen->saveSuccessValidacionByEstado(  $dataCDR[1], $dataCDR[2]  );
			$status = true;
		}
    
		$this->setMessageCode($message, $status, $code );
	}

	public function establishCommunicateError()
	{
		$data = $this->getCommunicate()['commnucate_data'];
		

		if ($this->getProveedor() == ResolverWsld::NUBEFACT) {
			if (get_class($data) == "SoapFault") {
				$code = $data->faultstring;
				$message = '(' . $code . ') ' . $data->detail->message;
			} else {
				$message = "Error imprevisto comuniqueme con el administrador";
			}
		}

		if ($this->getProveedor() == ResolverWsld::SUNAT) {
			if (get_class($data) == "SoapFault") {
				$code = optional($data)->faultcode;
				$faultstring = optional($data)->faultstring;
				$message = '(' . $code . ') ' . $faultstring;
			} else {
				$message = "Error imprevisto comuniqueme con el administrador";
			}
		}

		$this->setMessageCode( $message, false, 400);
		$this->resumen->DocCEsta = $code;
	}	
}