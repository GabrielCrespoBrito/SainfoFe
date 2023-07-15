<?php
namespace App\Http\Controllers\Traits;

use SoapFault;
use App\Empresa;
use App\OpcionUrl;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use App\Util\Sunat\Request\ResolverWsld;

trait UtilHelper
{
	public static function newSunatRequest( $is_cdr = false  , $is_guia = false , $data = [] , $empcodi = null )
	{
		$response['status'] = 0;
		$response['message'] = '';
		$response['client'] = '';
		$response['code'] = '200';
		$response['code_http'] = '200';

		libxml_disable_entity_loader(false);    
		// $ruc_empresa = session('empresa') ?? '001';
		$empcodi = $empcodi ??  '001';
		$empresa = Empresa::find( $empcodi );

		// Verificar el cdr mediante pruebas
		if( count($data) ){
			$user = $data['ruc'] . $data['usuario_sol'];
			$password = $data['clave_sol'];
			if( isset($data['ticket']) ){
				$wsdl = getXmlProduccion();
			}
			else {
				$wsdl = $empresa->cdrUrl();
			}
		}	
		
		// Enviar y verificar el cdr mediante el usuario
		else {
			
			$user = strtoupper($empresa->EmpLin1 . $empresa->FE_USUNAT);
			$password = $empresa->FE_UCLAVE;

			if( $is_cdr ){
				$wsdl = $empresa->cdrUrl();
			}
			else {
	      $wsdl = $empresa->urlSent($is_guia);
			}		
		}

		try {
			$auth = sprintf('<wsse:Security mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-' .
				'200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401' .
				'-wss-wssecurity-utility-1.0.xsd"><wsse:UsernameToken wsu:Id="UsernameToken-EBDA6BCF18BE1AEBD51424373' .
				'01228913"><wsse:Username>%s</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/' .
				'01/oasis-200401-wss-username-token-profile-1.0#PasswordText">%s</wsse:Password></wsse:UsernameToken>' .
				'</wsse:Security>', $user, $password );
			$authValues = new \SoapVar( $auth , XSD_ANYXML );


			$options = [
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
	          'verify_peer' => false,
	          'verify_peer_name' => false,
	          'allow_self_signed' => true
        	]
    		]),
				'exceptions' => true,
			];


			// echo file_get_contents($wsdl);
			// die();

			$client = new \SoapClient($wsdl, $options);
			$header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd','Security', $authValues, false);
			$client->__setSoapHeaders($header);
			$response['status'] = 1;
			$response['client'] = $client;
		} 
		catch( Throwable | SoapFault $e){
			$response['message'] = "Exception SunatRequest " . $e->getMessage();
			$response['code'] = '400';
			$response['code_http'] = '400';
			error_clear_last();
		}

		return $response;
	}

}
