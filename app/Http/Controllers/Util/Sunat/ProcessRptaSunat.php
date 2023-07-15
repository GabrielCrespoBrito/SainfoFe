<?php

namespace App\Http\Controllers\Util\Sunat;

use App\ErrorSunat;

trait ProcessRptaSunat 
{
	public function getRpta()
	{
		$document_data = $this->documentoRpta;
		$document_data['data_sunat'] = $this->data;
		$this->saveDocumento($document_data);		
		return $document_data;
	}

	public function processRespuesta( $sent , $isRepeat = false )
	{
		if( is_object($sent) ){

			$this->documentoRpta['busqueda_exitosa'] = true;

			if( $this->isFactura ){
				$this->processFactura($sent);
			}

			else {				
				$this->processTicket($sent);
			}

			return;
		}

		// Es un documento con un ticket ya repetido
		if( $isRepeat ){
			$this->processRepeat();
		}

		// Documento con errores
		else if( count($sent) ){
			$this->processError($sent);
		}

		// Boleta que no ha sido enviada y no nada que procesar
		else {
			$this->processEmpty($sent);
		}
	}


	public function processError($sent){
		$this->documentoRpta['busqueda_exitosa'] = false;
		$this->busqueda_errorMessage = $sent['message'];
	}

	public function processRepeat(){
		foreach( $this->documento_register as $documento ){
			if( $documento['ticket'] == $this->documentoRpta['ticket'] && $documento['busqueda_exitosa'] ){
			$this->documentoRpta['busqueda_exitosa'] = $documento['busqueda_exitosa'];
			$this->documentoRpta['encontrado_sunat'] = $documento['encontrado_sunat'];
			$this->data_sunat = $documento['data_sunat'];
			break;
			}
		}
	}

	public function processEmpty(){
		$this->documentoRpta['busqueda_exitosa'] = false;
		$this->documentoRpta['buscado_sunat'] = false;
		$this->busqueda_errorMessage = "";
	}

	public function processTicket($sent){

		if( get_class($sent) == "stdClass" ){

			$this->data['statusCode'] = $code = (int) $sent->status->statusCode;	
			$this->data['statusMessage'] = isset($sent->status->statusMessage) ? $sent->status->statusMessage : '';

			if( $code  == "0" || $code == "98"  ){

				$this->documentoRpta['encontrado_sunat'] = true;

				$content = $sent->status->content;
				$nameFile = getFilenameFromZip($sent->status->content);				
				$info = extraer_from_content($content , $nameFile , ['ResponseCode', 'Description']);		
				$this->documentoRpta['encontrado_sunat'] = true; 
				$this->data['rpta'] = $info[0];
				$this->data['description'] = $info[1];
			}

			else {
				$this->data['rpta'] = $code;
				$this->data['description'] = get_error_sunat($code);
			}

			$this->regiterTicket();
		}

		elseif( get_class($sent) == "SoapFault" ){				
			$this->documentoRpta['encontrado_sunat'] = false; 
		}
	}


	public function processFactura($sent){

		if( get_class($sent) == "stdClass" ){

			$this->data['statusCode'] = (int) $sent->statusCdr->statusCode;
			$this->data['statusMessage'] = $sent->statusCdr->statusMessage;
			$this->documentoRpta['encontrado_sunat'] = $this->data['statusCode'] == 4 ? true : false;

			if( isset($sent->statusCdr->content) ){
				$info = extraer_from_content( $sent->statusCdr->content, $this->getNameFile('.xml','R-'), ['ResponseCode', 'Description'] );
				$this->data['description'] = $info[1];
				$this->data['rpta'] = $info[0];
			}
		}

		elseif( get_class($sent) == 'SoapFault' ){
			$code_error = end( explode( ".", $sendBill->faultcode ) );
			$this->data['rpta'] = $code_error;
			$this->data['description'] = ErrorSunat::find($code_error)->nombre;
		}

	}

}
