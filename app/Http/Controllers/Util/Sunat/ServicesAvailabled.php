<?php 

namespace App\Http\Controllers\Util\Sunat;

class SunatServices 
{
	const BILL = 'bill';
	const BILL_GUIDE = 'billGuide';
	const CONSULT = 'consult';

	protected $services = [ self::BILL, self::BILL_GUIDE , self::CONSULT ];
/*
	'0', 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl'
	'1', 'HOMOLOGACIÓN'
	'2', 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl'
	'3', 'https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl'
	'4', 'https://e-guiaremision.sunat.gob.pe:443/ol-ti-itemision-guia-gem/billService?wsdl'
	'5', 'https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl'
*/ 


	public function __construct( $service )
	{
		$service = $service;
	}

	public static function isAvailabledService($service)
	{
		return in_array($this->service, self::all() );
	}

	public function isBill()
	{
		return $this->service === self::BILL;
	}

	public function isBillGuide()
	{
		return $this->service === self::BILL;
	}

	public function isConsult()
	{
		return $this->service === self::BILL;
	}

	public function consultUrl()
	{

	}

	public function wsld ()
	{
		
	}


} 