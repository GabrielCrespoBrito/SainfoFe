<?php

namespace App\Util\Sunat\Services\SunatSendSummary;


use App\Util\Sunat\Request\credentials\CredentialInterface;

/**
 * Clase para envio del resumenes 
 * 
 */
class SendSummaryNube  extends SendSummary
{
  public function __construct( CredentialInterface $credential, bool $isProduction = false )
  {
    parent::__construct();

    $this->serviceRequestInit('sendBill', $isProduction, $credential, 'nubefact');
  }
}
