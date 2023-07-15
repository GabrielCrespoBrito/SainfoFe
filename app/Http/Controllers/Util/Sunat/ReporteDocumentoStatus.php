<?php

namespace App\Http\Controllers\Util\Sunat;

use App\Http\Controllers\Traits\SunatHelper;
use App\Http\Controllers\Util\Xml\XmlHelperNew;

class ReporteDocumentoStatus
{
	public $data_empresa;
	public $fecha;

	public function __construct($fecha, $ruc, $nombre ){
		$this->fecha = $fecha;
		$this->data_empresa = [
			'ruc' => $ruc,
			'nombre' => $nombre
		];
	}
	

	public $data = [
		'cant_documentos' => 0,
		'busqueda_exitosas' => 0,
		'encontrados_sunat' => 0,
		'documentos' => [],
	];

	public function sumCantidadDocumentos(){
		$this->data['cant_documentos']++; 
	}

	public function sumBusquedaExitosaSunat($resp){
		$this->data['busqueda_exitosas'] += (int) $resp; 
	}

	public function sumEncontradoSunat($resp){
		$this->data['encontrados_sunat'] += (int) $resp; 
	}


	public function addDocumento($documento){
		array_push( $this->data['documentos'] , $documento );
	}

	public function loadDataDocument( $document ){
		$this->sumCantidadDocumentos();
		$this->sumBusquedaExitosaSunat( $document['busqueda_exitosa'] );
		$this->sumEncontradoSunat( $document['encontrado_sunat'] );
		$this->addDocumento($document);
	}

	public function getData(){
		$this->data['empresa'] = $this->data_empresa;
		$this->data['fecha'] = $this->fecha;
		return $this->data;
	}

}
