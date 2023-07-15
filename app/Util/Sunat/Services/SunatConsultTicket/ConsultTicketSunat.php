<?php

namespace App\Util\Sunat\Services\SunatConsultTicket;

use App\Util\Sunat\Request\credentials\CredentialInterface;
use App\Util\Sunat\Request\ResolverWsld;

/**
 * Clase consultar 
 * 
 */
class ConsultTicketSunat  extends ConsultTicket
{
  public function __construct(CredentialInterface $credential, bool $isProduction = false)
  {
    parent::__construct();

    $this->serviceRequestInit( ResolverWsld::SERVICE_CONSULT_STATUS , $isProduction, $credential, ResolverWsld::SUNAT);
  }
}