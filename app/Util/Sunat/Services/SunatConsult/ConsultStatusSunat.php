<?php
namespace App\Util\Sunat\Services\SunatConsult;


use App\Util\Sunat\Request\credentials\CredentialInterface;
use App\Util\Sunat\Request\ResolverWsld;
use App\Util\Sunat\Services\SunatConsult\ConsultStatus;

/**
 * Clase consultar 
 * 
 */
class ConsultStatusSunat  extends ConsultStatus
{
  public function __construct(CredentialInterface $credential, bool $isProduction = false, bool $cdr)
  {
    parent::__construct( $cdr );    
    
    $this->serviceRequestInit(ResolverWsld::SERVICE_CONSULT_CDR, $isProduction, $credential, ResolverWsld::SUNAT);
  }
}
