<?php 

namespace App\Util\Sunat\Services;

use SoapFault;
use Illuminate\Support\Facades\Log;
use App\Util\Sunat\Request\SunatRequest;
use App\Util\Sunat\Services\ServicesSunat;
use App\Http\Controllers\Util\Sunat\SunatServices;
use App\Util\Sunat\Request\credentials\CredentialInterface;

abstract class SunatCommunicate implements SunatComunicateInterface
{
	/**
	 * Servicio 
	 *
	 * @var string
	 */
	protected $service; 

	/**
	 * Parametro para la comunicación
	 *
	 * @var array
	 */
	protected $params; 

	/**
	 * Establecer comunicación con el cliente de la sunat 
	 * 
	 * @var array
	 * */
	protected $sunatRequest;

	/**
	 * Respuesta de la comunicación a la sunat
	 * 
	 * @var array
	 */
	protected $response = [
		'client_connection_success' => true,
		'client_connection_response' => [],
		'communicate' => true,
		'commnucate_data' => [],
	];

	/**
	 * Parametros para la comunicación
	 *
	 * @return void
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Parametros para la comunicación
	 *
	 * @return mixed
	 */
	public function setParams($params)
	{
		$this->params = $params;

		return $this;
	}

	/**
	 * Establecer el servicio  al cual comunicarse
	 *
	 * @return void
	 */
	public function setService($service)
	{
		if( !ServicesSunat::validService($service)){
			throw new \Exception("The service $service is not valid", 1);
		}

		$this->service = $service;

		return $this;
	}

	/**
	 * Servicio al cual comunicarse para la comunicación
	 *
	 * @return void
	 */
	public function getService()
	{
		return str_replace( " " , '' ,  $this->service );
	}

	public function serviceCommunication()
	{
		return $this->response['client_connection_response']['client']
		->__soapCall(
			$this->getService(), 
			$this->getParams() 
		);
	}

	/**
	 * Validar que todos los parametros para la conexión existan
	 *
	 * @return Exception|mixed
	 */
	public function validateCommunication()
	{
		if ($this->sunatRequest === null) 
		{
			throw new \Exception("No se puede establecer comunicación sin establecer los parametros de conexión ", 1);
		}

		if($this->service === null )
		{
			throw new \Exception("No se especifico el servicio a comunicarse", 1);
		}

		if ($this->getParams() === null) 
		{
			throw new \Exception("No se especifico los/el parametros", 1);
		}
	}

	/**
	 * Comunicación con la sunat, primero hacemos la petición para el envio con el sunatRequest, luego con el cliente la comunicación con el servicio especifico
	 *
	 * @return $this
	 */
	public function communicate()
	{
		// Validar que todos los parametros sean suministrados
		$this->validateCommunication();

		# Petición al cliente de la sunat 
		if( $this->request() ){

			try {
				# Comunicación con el servicio especifico
				$response = $this->serviceCommunication();

				$this->response['communicate'] = true;
				$this->response['commnucate_data'] = $response;

			} catch (Throwable | SoapFault $th) {
				$this->response['communicate'] = false;
				$this->response['commnucate_data'] = $th;
			}
		}

		return $this;
	}

	/**
	 * Instanciar el sunatRequest
	 * 
	 * @param string $wsld sunat|sunat_oficinal|nube_fact ...
	 * @param bool  $isProduction
	 * @param credential $isProduction
	 * @return boolean
	 */
	public function serviceRequestInit($wsld, $isProduction, CredentialInterface $credential, $proveedor)
	{		
		$this->sunatRequest = new SunatRequest($wsld, $isProduction, $credential, $proveedor);
		return $this;
	}

	/**
	 * Comunicación con el  cliente
	 * 
	 * @return bool
	 */
	protected function request()
	{
		$this->sunatRequest->communicate();
		$response = $this->sunatRequest->response();

		$this->response['client_connection_success'] = $response['status'];
		$this->response['client_connection_response'] = $response;
		return $response['status'];
	}

	/**
	 * Obtener respuesta de la comunicación con la sunat
	 * 
	 * @return void
	 */
	public function getResponse() 
	{	
		return $this->response;
	}
}