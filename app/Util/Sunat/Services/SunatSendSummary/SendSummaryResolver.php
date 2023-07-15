<?php

namespace App\Util\Sunat\Services\SunatSendSummary;

use App\Util\Sunat\Request\ResolverWsld;
use App\Util\Sunat\Services\SunatResolverService;
use App\Util\Sunat\Request\credentials\CredentialInterface;

/**
 * Clase para envio del resumenes 
 * 
 */
class SendSummaryResolver  extends SunatResolverService
{
  public function __construct($proveedor, CredentialInterface $credential, bool $isProduction = false)
  {
    switch ($proveedor) {
      case ResolverWsld::NUBEFACT:
        $communcator = new SendSummaryNube($credential, $isProduction);
        break;
      case ResolverWsld::SUNAT:
        $communcator = new SendSummarySunat($credential, $isProduction);
        break;
      default:
        throw new \Exception("{$proveedor} No existe", 1);
        break;
    }

    $this->setCommunicator($communcator);
  }
}
