<?php

namespace App\Util\Sunat\Services\SunatConsultTicket;

use App\Util\Sunat\Services\SunatCommunicate;

/**
 * Clase consultar 
 * 
 */
abstract class ConsultTicket  extends SunatCommunicate
{
  public function __construct()
  {
    $this->setService('getStatus');
  }
}
