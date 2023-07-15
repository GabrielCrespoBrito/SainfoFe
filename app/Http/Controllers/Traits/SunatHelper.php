<?php

namespace App\Http\Controllers\Traits;
use App\ErrErrorCode;
use Exception;

/**
 * Helper que permite el procesamiento relacionado a la SUNAT y manejo de respuestas, implica almacenamiento en DB.
 * 
 * @return bool
 * @return string
 * @return array
 * @return object
 * @return float
 * @return int
 * @return callback
 * @return int
 */

trait SunatHelper
{
	public static function ticket($cTicket, $nameFile = "" , $sin_guardar = false , $data = [] )
	{
		$response['status'] = 1;
		$response['message'] = '';
		$response['path'] = '';
		$response['code'] = null;
		$response['code_http'] = 400;

		try {
			
			# Request
			$sunatRequest = UtilHelper::newSunatRequest(false,false,$data);

			# No se realizo la comunicación con la sunat
			if(!$sunatRequest['status']) throw new Exception($sunatRequest['message']);	

			$client = $sunatRequest['client'];
			// $getStatus = $client->__soapCall('getStatus', [['ticket' => $cTicket ]] );
			$getStatus = $client->__soapCall('getStatus', [['ticket' => $cTicket ]] );

			# Si solo se quiere la respuesta
			if( $sin_guardar ) return $getStatus;

			switch (get_class($getStatus)) {
				case 'stdClass':
					$response['code'] = $getStatus->status->statusCode;
					switch ($response['code']){
						case '99':
						case '0':
						$file = $getStatus->status->content;
						$response['status'] = 1;						
						$response['response'] = $getStatus;
						$response['content'] = $file;
						$response['code_http'] = 200;
						FileHelper()->save_cdr( $nameFile , $file );
						break;
						case '98':						
					$response['status'] = 0;
					}
					break;
				case 'SoapFault':
					$error = $getStatus->faultcode;
					$errorExplode = explode('.', $error);
					$errorCode = end($errorExplode);					
					$errErrorCode = ErrErrorCode::find($errorCode);
			}
		}
		catch (Exception $e) {
			$response['message'] = $e->getMessage();
			$response['status'] = 0;
		}

		return $response;
	}

	public static function getStatusCdr($params, $name , $guardar = true , $data = [] , $empcodi = null ) 
	{
		$response = [
			'status'  => 1,
			'message' => '',
			'path' 		=> '',
			'ticket'  => '',
			'code'    => 500,
			'code_http'    => 400,
			'file' 		=> '',		
		];

		
		$client = UtilHelper::newSunatRequest( true , false , $data , $empcodi );

		try {
			if (!$client['status']) {
				throw new Exception($client['message']);
			}

		$getStatusCdr = $client['client']->__soapCall('getStatusCdr', array($params));

	  if(!$guardar){
		  return $getStatusCdr;
	  }

			switch (get_class($getStatusCdr)) {
				case 'stdClass':					
					$codigo = (int) $getStatusCdr->statusCdr->statusCode;

					switch($codigo){            
						case 127 : 
						case 125 :
						$response['message'] = $codigo;
						break;
					default :
						$fileHelper = FileHelper(get_empresa('EmpLin1'));
						$file = $getStatusCdr->statusCdr->content;
						$nameCdr = 'R-' . $name;
						$response['status'] = 1;
						$response['code'] = 200;
						$response['content'] = $file;
						$response['code_http'] = 200;
						$fileHelper->save_cdr( $nameCdr , $file );
						break;
					}
				break;

				case 'SoapFault':
					$error = $getStatusCdr->faultcode;
					$response['message'] = $error;					
					// $response['status'] = 0;
				break;
			}
		} 

		catch (Exception $exc ) {
			$response['message'] = $exc->getMessage();
			// $response['status'] = 0;
		}

		return $response;
	}

	/**
	 * Env�o de documento a la SUNAT
	 * @param string $path
	 * @param int $id
	 .
	 * @return array
	 * @throws Exception
	 */
	public static function sent( $nameFile, $content , $is_cdr = false, $is_guia = false , $empcodi = null )
	{
		set_time_limit(60);
		
		$response = [
			'status'  => 0,
			'message' => '',
			'path' 		=> '',
			'ticket'  => '',
			'code'    => 500,
			'file' 		=> '',
			'code_http'=> "400"
		];
    // $sent = SunatHelper::sent( $nameFile , $content_zip_firmado , false , false, $empcodi );

		$name = $nameFile;
		$nameExplode = explode('-', $name);    
		$tipo_documento = $nameExplode[1];
		$sunatRequest = UtilHelper::newSunatRequest( $is_cdr , $is_guia, [] , $empcodi );

		// dd($sunatRequest);

		if( $sunatRequest['status'] == 0 ){
			return $sunatRequest;
		}

		$client = $sunatRequest['client'];		
		$params['contentFile'] = $content;
		$params['fileName'] = $name;
		switch ($tipo_documento){
			case '01':
			case '03':
			case '07':
			case '08':
			case '09':
			$sendBill = "";
				try {
					$sendBill = $client->__soapCall('sendBill', array($params));
					// dd( "sendBill regular" ,$sendBill);
				} catch( Thowable | \ErrorException |  \SoapFault $e ){
					$sendBill = $e;
					// dd( "sendBill faul" ,$sendBill);
				}
				
				switch (get_class($sendBill)) {
					case 'stdClass':
						$file = $sendBill->applicationResponse;
						$nameCdr = 'R-' . $name;            
						$response['status'] = 1;
						$response['code_http'] = 200;											
						$response['path'] = "";
						$response['content'] = $file;            
						FileHelper(get_empresa('EmpLin1'))->save_cdr( $nameCdr , $file );
						break;  
					case 'SoapFault':
						if( in_array( $tipo_documento , ["01","03","07", "08"] )  ){
							if( $message = optional(optional($sendBill)->detail)->message ){
								$code_error = $sendBill->faultstring;
							}
							else {
								if( strpos(".", $sendBill->faultcode) !== false ){
									$code_error = explode(".", $sendBill->faultcode)[1];
								}
								else {
									$code_error = $sendBill->faultcode;
								}
							}
							$response['code_error'] = $code_error;
							$response['error_message'] = $sendBill->faultstring;						
							$response['message'] = $response['code_error'];						
							$response['code'] = 400;
						}
						else {
							$response['message'] = $sendBill->detail->message;
							$response['code'] = $sendBill->faultstring;
						}
						return $response;
				}
				break;
			case 'RC':
			case 'RA':
				try {
					$sendSummary = $client->__soapCall('sendSummary', array($params));
				} catch( Thowable | \ErrorException |  \SoapFault $e ){
					$sendSummary = $e;
				}

				switch (get_class($sendSummary)) {
					case 'stdClass':
							$response['status']  = 1;
							$response['code']    = 200;
							$response['code_http']    = 200;							
							$response['message'] = $sendSummary->ticket;
						break;
					case 'SoapFault':
						$error = $sendSummary->faultcode;	
						$errorExplode = explode('.', $error);						
						$errorCode = end($errorExplode);
						$response['code'] = $errorCode;		
						$response['code_http'] = "400";
						$response['message'] = $sendSummary->faultstring;
						return $response;
				}
				break;
		}

		return $response;
	}

}