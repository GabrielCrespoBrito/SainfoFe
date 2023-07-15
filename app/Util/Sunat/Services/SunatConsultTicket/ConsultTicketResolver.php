<?php
namespace App\Util\Sunat\Services\SunatConsultTicket;


use App\Util\Sunat\Request\ResolverWsld;
use App\Util\Sunat\Services\SunatResolverService;
use App\Util\Sunat\Request\credentials\CredentialInterface;
use App\Util\Sunat\Services\SunatConsultTicket\ConsultTicketNube;
use App\Util\Sunat\Services\SunatConsultTicket\ConsultTicketSunat;

/**
 * Clase para envio del resumenes 
 * 
 */
class ConsultTicketResolver  extends SunatResolverService
{
  public function __construct($proveedor, CredentialInterface $credential, bool $isProduction = false)
  {
    switch ($proveedor) {
      case ResolverWsld::NUBEFACT:
        $communcator = new ConsultTicketNube($credential, $isProduction);
        break;
      case ResolverWsld::SUNAT:
        $communcator = new ConsultTicketSunat($credential, $isProduction);
        break;
      default:
        throw new \Exception("{$proveedor} No existe", 1);
        break;
    }
    $this->setCommunicator($communcator);
  }
}
