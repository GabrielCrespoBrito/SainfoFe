<?php

namespace App\Util\Sunat\Request;

use App\Util\Sunat\Request\credentials\CredentialInterface;
use Illuminate\Support\Facades\Log;
use SoapFault;

/**
 * Comunicación Soap para obtener el cliente con el cliente con el cual comunicarse con la sunat
 * 
 */
class SunatRequest
{
	/**
	 * Response of the request
	 * 
	 * @var array
	 */
	private $response = [
		'status' => false,
		'error' => '',
		'client' => null,
	];

	/**
	 * Interface para obtener las credenciales
	 * 
	 * @var App\Util\Sunat\Request\credentials\CredentialInterface;
	 */
	protected $sunatCredential;

	/**
	 * String de autentificación con el usuario y clave sol
	 * 
	 * @var  string
	 */
	protected $authValue;

	/**
	 * Plantilla de la cabecera de la autentificación con el servicio
	 * 
	 */
	const AUTH = '<wsse:Security mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:UsernameToken wsu:Id="UsernameToken-EBDA6BCF18BE1AEBD5142437301228913"><wsse:Username>%s</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">%s</wsse:Password></wsse:UsernameToken></wsse:Security>';

	/**
	 * Parameters of soapHeader
	 * 
	 */
	const HEADER = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
	const SECURITY = "Security";

	/**
	 * @param string $wsld
	 * @param bool $isProduction
	 * @param CredentialInterface $service
	 * @param string $proveedor
	 */
	public function __construct(String $wsld, Bool $isProduction = true, CredentialInterface $credential , $proveedor)
	{
		$this->isProduction = $isProduction;
		$this->sunatCredential = $credential;
		$this->wsdlResolver = new ResolverWsld($wsld, $isProduction, $proveedor);
	}

	/**
	 * Options of connections
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		return [
			'uri' => 'http://schemas.xmlsoap.org/soap/envelope/',
			'encoding' => 'UTF-8',
			'style' => SOAP_RPC,
			'use' => SOAP_ENCODED,
			'soap_version' => SOAP_1_1,
			'cache_wsdl' => WSDL_CACHE_NONE,
			'connection_timeout' => 0,
			'trace' => true,
			'stream_context' =>
			stream_context_create([
					'ssl' => [
	          'ciphers'=>'AES256-SHA',
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					]
				]
			),
			'exceptions' => true,
		];
	}
  
	/**
	 * Obtener string de autentificación con datos de usuario y contraseñ
	 * 
	 * @return string
	 */
	public function getAuthRequest()
	{
		return sprintf(
			self::AUTH,
			$this->sunatCredential->getUsername(),
			$this->sunatCredential->getPassword()
		);
	}

	/**
	 * SoapVar
	 * @return SoapVar
	 * 
	 */
	public function soapVar()
	{
		return new \SoapVar( $this->getAuthRequest() , XSD_ANYXML);    
	}

	/**
	 *  SoapClient
	 * @return SoapClient
	 * 
	 */
	public function soapClient()
	{
		return new \SoapClient($this->wsdlResolver->getWsdl(), $this->getOptions());
	}

	/**
	 *  Soap Header
	 * @return SoapHeader
	 * 
	 */
	public function soapHeader($authValues)
	{
		return new \SoapHeader(self::HEADER, self::SECURITY, $authValues, false);
	}

	/**
	 * 
	 *
	 * @return void
	 */
	public function communicate()
	{
		libxml_disable_entity_loader(false);		
		try {
			$authValues = $this->soapVar();
			$client 		= $this->soapClient();
			$header 		= $this->soapHeader($authValues);
			$client->__setSoapHeaders($header);			
			$this->response['status'] = true;
			$this->response['client'] = $client;
		} catch (\Throwable | SoapFault $e) {
			$this->response['error'] = $e->getMessage();
			error_clear_last();
		}
	}

	public function response()
	{
		return $this->response;
	}
}