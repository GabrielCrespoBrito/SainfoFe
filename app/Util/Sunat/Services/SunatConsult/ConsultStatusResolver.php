<?php
namespace App\Util\Sunat\Services\SunatConsult;


use App\Util\Sunat\Request\ResolverWsld;
use App\Util\Sunat\Services\SunatResolverService;
use App\Util\Sunat\Request\credentials\CredentialInterface;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusNube;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusSunat;

/**
 * Clase para envio del resumenes 
 * 
 */
class ConsultStatusResolver  extends SunatResolverService
{
  public function __construct($proveedor, CredentialInterface $credential, bool $isProduction = false , bool $cdr = false)
  {
    switch ($proveedor) {
      case ResolverWsld::NUBEFACT:
        $communcator = new ConsultStatusNube($credential, $isProduction, $cdr);
        break;
      case ResolverWsld::SUNAT:
        $communcator = new ConsultStatusSunat($credential, $isProduction, $cdr);
        break;
      default:
        throw new \Exception("{$proveedor} No existe", 1);
        break;
    }

    $this->setCommunicator($communcator);
  }
}
