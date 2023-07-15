<?php

namespace App\Util\Sunat\Services\SunatSendSummary;


use App\Util\Sunat\Request\credentials\CredentialInterface;
use App\Util\Sunat\Request\ResolverWsld;

/**
 * Clase para envio del resumenes 
 * 
 */
class SendSummarySunat  extends SendSummary
{
  public function __construct(CredentialInterface $credential, bool $isProduction = false)
  {
    parent::__construct();

    $this->serviceRequestInit('sendBill', $isProduction, $credential,  ResolverWsld::SUNAT );
  }
}
