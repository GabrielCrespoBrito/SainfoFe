<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Util\Sunat\Services\ServicesParams;
use App\Util\Sunat\Request\credentials\CredentialManual;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusSunat;
use Illuminate\Support\Facades\Log;

class SolRule implements Rule
{
	public $ruc;
	public $usuario_sol;
	public $clave_sol;
	public $message = 'No se pudo validar su clave sol';
	
	const CODE_AUTH = [
		'0100',//	El sistema no puede responder su solicitud. Intente nuevamente o comuníquese con su Administrador 
		'0101',//	El encabezado de seguridad es incorrecto 
		'0102',//	Usuario o contraseña incorrectos 
		'0103',//	El Usuario ingresado no existe 
		'0104',//	La Clave ingresada es incorrecta
		'0105',//	El Usuario no está activo 
		'0106',//	El Usuario no es válido 
		'0109',//	El sistema no puede responder su solicitud. (El servicio de autenticación no está disponible) 
		'0110',//	No se pudo obtener la informacion del tipo de usuario 
		'0111',//	No tiene el perfil para enviar comprobantes electronicos 
		'0112',//	El usuario debe ser secundario 
		'0113',//	El usuario no esta afiliado a Factura Electronica 
	];
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct($ruc, $usuario_sol, $clave_sol)
	{
		$this->ruc = $ruc;
		$this->usuario_sol = $usuario_sol;
		$this->clave_sol = $clave_sol;
	}

	public function isErrorCredenciales($code)
	{
		$codeReal = explode(":", $code);
		$codeReal = end($codeReal);
		
		// Si el codigo de error es un codigo de error por la autentificacion
		return in_array($codeReal , self::CODE_AUTH);
	}

	public function paramsTestBusqueda()
	{
		return ServicesParams::getFormatGetStatus(
			$this->ruc ,
			"01",
			"F001",
			"1"
		);
	}

	public function getCredential()
	{
		$usuario_sol_real = $this->ruc . $this->usuario_sol;
		return new CredentialManual( $usuario_sol_real  , $this->clave_sol );
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
		$communicator = new ConsultStatusSunat($this->getCredential() , true, false );

		$response =
		$communicator
		->setParams($this->paramsTestBusqueda())
		->communicate()
		->getResponse();

		$client_connection = $response['client_connection_response'];

		if( $response['client_connection_success'] == false){
			return false;
		}

		if( $client_connection['status'] == false ){
			$this->setMessage($client_connection['error']);
			return false;
		}

		
		if( $response['communicate'] == false ){
			$data =	$response['commnucate_data'];
			if(is_object($data)){
				$className = get_class($data);
				if( $className === "SoapFault"){
					$code = $data->faultcode;
					$stringError = $data->faultstring;
					$messageUser = $stringError;
					if($this->isErrorCredenciales($code)){
						$this->setMessage($messageUser);
						return false;
					}
				}	
			}
		}

		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return $this->message;
	}

	public function setMessage($message = "")
	{
		$this->message = $message;
	}

}