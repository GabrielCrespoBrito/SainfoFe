<?php 

namespace App\Util\Sunat\Request;

use App\Util\Sunat\Request\wsdl\WsldNubeFactOse;
use App\Util\Sunat\Request\wsdl\WsldSunatOficial;
use App\Util\Sunat\Request\wsdl\WsldSunatOficialLocal;
use Illuminate\Support\Facades\Log;

class ResolverWsld
{
	const SUNAT = "sunat";
	const NUBEFACT = "nubefact";

	const SERVICE_SENDBILL = "sendBill";
	const SERVICE_SENDBILL_GUIDE = "sendBillGuide";
	const SERVICE_CONSULT_STATUS = "consultStatus";
	const SERVICE_CONSULT_CDR = "consultStatusCdr";

	/**
	 * Servicios para la consulta o envio de documentos
	 * 
	 * @var string 'sendBill' 'sendBillGuide 'consult'
	 */
	public $service;

	/**
	 * Si se require que sea de producción o desarrollo
	 * 
	 * @var bool 
	 */
	public $isProduction;

	/**
	 * Proveedor para obtener el el wsld
	 * 
	 * @var class
	 */
	public $proveedor;

	/**
	 * Determine proveedor
	 *
	 * @return 
	 */
	public function resolveProveedor($proveedor)
	{
		switch ($proveedor) {
			case 'sunat':
				$this->proveedor = new WsldSunatOficial();
				break;
			case 'sunat_local':
				$this->proveedor = new WsldSunatOficialLocal();
				break;
			case 'nubefact':
				$this->proveedor = new WsldNubeFactOse();
				break;

			default:
				throw new \Exception("No existe este proveedor", 1);
				break;
		}		
	}


	/**
 	* Obtener el generador la clase para obtener 
 	* 
 	*/
	function __construct( $service , bool $isProduction , $proveedor = "sunat")
	{
		$this->service = $service;
		$this->isProduction = $isProduction;
		$this->resolveProveedor($proveedor);
	}

	/**
	 * Obtener el wsld deacuerdo al estado y al servicio
	 * @param string $service 
	 * @return mixed
	 */
	public function getWsdl()
	{
    $wsld = null;

    switch ($this->service) {
			
			case self::SERVICE_SENDBILL:
				$wsld = $this->isProduction ? $this->proveedor->getBillServiceProduction() : $this->proveedor->getBillServiceBeta();
				break;

			case self::SERVICE_SENDBILL_GUIDE:
				$wsld = $this->isProduction ? $this->proveedor->getBillServiceGuiaProduction() : $this->proveedor->getBillServiceGuiaBeta();
				break;

			case 'consult':
				$wsld = $this->isProduction ? $this->proveedor->getProductionConsult() : $this->proveedor->getBetaConsult();
				break;

			case self::SERVICE_CONSULT_STATUS:
				$wsld = $this->isProduction ? $this->proveedor->getProductionConsultStatus() : $this->proveedor->getBetaConsult();
				break;

			case self::SERVICE_CONSULT_CDR:
				$wsld = $this->isProduction ? $this->proveedor->getProductionConsultCdr() : $this->proveedor->getBetaConsult();
				break;	

			default:
				throw new \Exception("Este servicio no existe", 1);
				break;
		}

    return $wsld;

	}

}
