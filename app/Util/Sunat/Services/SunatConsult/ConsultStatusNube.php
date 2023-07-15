<?php
namespace App\Util\Sunat\Services\SunatConsult;


use App\Util\Sunat\Request\ResolverWsld;
use App\Util\Sunat\Services\SunatConsult\ConsultStatus;
use App\Util\Sunat\Request\credentials\CredentialInterface;

/**
 * Clase consultar 
 * 
 */
class ConsultStatusNube  extends ConsultStatus
{
  public function __construct(CredentialInterface $credential, bool $isProduction = false, bool $cdr = false )
  {
    parent::__construct($cdr);


    $wsld = $cdr ? ResolverWsld::SERVICE_CONSULT_CDR : ResolverWsld::SERVICE_CONSULT_STATUS;

    $this->serviceRequestInit('consult', $isProduction, $credential, ResolverWsld::NUBEFACT);
  }
}
