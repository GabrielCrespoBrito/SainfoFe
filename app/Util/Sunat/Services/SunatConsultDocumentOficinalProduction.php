<?php

namespace App\Util\Sunat\Services;

use App\Util\Sunat\Request\credentials\CredentialInterface;

/**
 * Consultar factura/nota credito,debito al servicio oficinal (no ose) de la sunat en producción
 * 
 */
class SunatConsultDocumentOficinalProduction extends SunatConsultDocument
{

  public function __construct( $proveedor, $credential, $service)
  {
    parent::__construct();
    
    // $this->serviceRequestInit('consult', true, $credential, 'sunat');
    // $this->service = $service;
    // $this->serviceRequestInit('sendBill', $isProduction, $credential, 'nubefact');
    // $this->serviceRequestInit('sendBill', $isProduction, $credential, 'nubefact');

    $this->serviceRequestInit('getStatus', $isProduction, $credential, 'nubefact' , $proveedor );    
  }


}