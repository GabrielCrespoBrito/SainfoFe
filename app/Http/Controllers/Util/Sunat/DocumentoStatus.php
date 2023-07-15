<?php

namespace App\Http\Controllers\Util\Sunat;

use App\Http\Controllers\Traits\SunatHelper;
use App\Http\Controllers\Util\Sunat\ProcessRptaSunat;
use App\Http\Controllers\Util\Sunat\VerifiySunat;
use App\Http\Controllers\Util\Xml\XmlHelperNew;
class DocumentoStatus
{
	use VerifiySunat, ProcessRptaSunat;

	const BOLETA = "03";

	// Tickets procesados para no tener que volver a buscar documentos con el mismo ticket
	public $listTickets = [];

	// Información para acceder a la información
	public $ruc_empresa;
	public $usuario_sol;
	public $usuarioreal_sol;
	public $clave_sol;
	public $save_cdr;

	// Guardado de documentos que se van buscando
	public $documento_register = [];

	// Data respuesta de la sunat
	public $data = [ 'statusCode' => '-' , 'statusMessage' => '-' , 'description' => '-' , 'rpta' => '-' ];

	public $documentLoaded = false;
	public $isFactura = false;

	// Formato de respuesta
	public $formatRptaData = [
		'numero_documento' => null,
		'tipo_documento'   => null,
		'ticket' 					 => '',
		'serie_documento'  => null,
		'buscado_sunat' 	 => true,
		'estado_documento' => null,
		'busqueda_exitosa' => false,
		'encontrado_sunat' => false,
		'data_sunat' 			 => null,
	];

	public $documentoRpta = [];

	
	// Set de parametros para acceder a las busquedas
	public $paramsVerify;
	public $paramsApp;

	// Respuesta de la busqueda
	public $busqueda_exitosa;
	public $busqueda_errorMessage = "";
	public $encontrado_sunat;
	public $buscado_sunat = true;	
	public $data_sunat;

	protected $rpta = "";

	public function __construct( $ruc_empresa , $usuario_sol , $clave_sol , $save_cdr = false ){
		$this->ruc_empresa = $ruc_empresa;
		$this->usuario_sol = $usuario_sol;
		$this->clave_sol = $clave_sol;
		$this->save_cdr = $save_cdr;
	}
	public function regiterTicket(){
		array_push($this->listTickets , $this->documentoRpta['ticket'] );
	}

	public function getListTickets(){
		return $this->listTickets;
	}

	public function isRepeatTicket(){
		return in_array($this->documentoRpta['ticket'], $this->getListTickets() );
	}

	public function setTypeDocument(){
		$this->isFactura = $this->documentoRpta['tipo_documento'] != self::BOLETA;
	}

	public function getFormatRptaData(){
		return $this->formatRptaData;
	}

	public function loadDocument( $documento ) {

		$this->documentoRpta = $this->getFormatRptaData();
		$this->documentoRpta['tipo_documento'] = $documento->TidCodi;
		$this->documentoRpta['serie_documento'] = $documento->VtaSeri;
		$this->documentoRpta['numero_documento'] = $documento->VtaNumee;	
		$this->documentoRpta['status'] = $documento->VtaEsta;
		$this->documentoRpta['fe_obse'] = $documento->fe_obse;
		$this->documentoRpta['ticket'] = $documento->fe_obse;
		$this->documentoRpta['estado_documento'] = $documento->fe_rpta;
		$this->documentLoaded = true;
		$this->setTypeDocument();
		$this->setCredentials();

		return $this;
	}


	public function setCredentials(){

    $this->paramsApp = [
      'ruc' => $this->ruc_empresa,
      'usuario_sol' => $this->usuario_sol,
      'clave_sol' => $this->clave_sol,
    ];

		// Factura
		if( $this->isFactura ){			
	    $this->paramsVerify = [
	      'rucComprobante' => $this->ruc_empresa,
	      'tipoComprobante' => $this->documentoRpta['tipo_documento'],
	      'serieComprobante' => $this->documentoRpta['serie_documento'],
	      'numeroComprobante' =>  $this->documentoRpta['numero_documento'],
	    ];
		}

		// Boleta
		else {
			$this->paramsApp['ticket'] = $this->documentoRpta['fe_obse'];			
		}
	}

	public function hasTicket(){
		return (bool) $this->documentoRpta['ticket'];
	}

	public function getNameFile( $ext = '.zip' , $prefix = "" ){
		if( $this->isFactura ){
			return $prefix . ($this->ruc_empresa . '-' . $this->documentoRpta['tipo_documento'] . '-' . $this->documentoRpta['serie_documento'] . '-' . $this->documentoRpta['numero_documento'] . $ext );
		}
		else {
			return $prefix . ($this->ruc_empresa . '-' . $this->documentoRpta['numero_documento'] . $ext );
		}
	}

	public function getParamsVerify(){
		return $this->paramsVerify;
	}

	public function getParamsApp(){
		return $this->paramsApp;
	}

	public function saveDocumento( $document ){
		array_push( $this->documento_register , $document );
	}


}