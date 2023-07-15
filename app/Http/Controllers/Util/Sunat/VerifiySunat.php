<?php

namespace App\Http\Controllers\Util\Sunat;

use App\Http\Controllers\Traits\SunatHelper;


trait VerifiySunat
{

	public function verifyStatus(){

		if( ! $this->documentLoaded ){
			dd("Error no se ha cargado el documento");
			exit;
		}

		if( $this->isFactura ){
			$this->verifyFactura();			
		}

		else {
			$this->verifyBoleta();
		}

    return $this->getRpta();		

	}

	public function verifyFactura(){

  	$sent = SunatHelper::getStatusCdr($this->getParamsVerify() , $this->getNameFile() , false , $this->getParamsApp() );
    $this->processRespuesta($sent);
	}

	public function verifyBoleta(){

		$sent = [];
		$is_repeat = false;
		if( $this->hasTicket() ){

			if( $this->isRepeatTicket() )	{ 
				$is_repeat = true;
			}

			else { 
				$sent = $this->verifiyTicket();
			}
		}

    $this->processRespuesta($sent, $is_repeat);
	}

	public function verifiyTicket(){
		return SunatHelper::ticket( $this->documentoRpta['ticket'] , "" , true , $this->getParamsApp());		
	}

}
