<?php
namespace App\Util\Sunat\Services\SunatConsult;

use App\Http\Controllers\Util\Sunat\SunatServices;
use App\Util\Sunat\Services\ServicesSunat;
use App\Util\Sunat\Services\SunatCommunicate;

/**
 * Clase consultar 
 * 
 */
abstract class ConsultStatus  extends SunatCommunicate
{
  public function __construct( bool $cdr = false )
  {
    $service = $cdr ? ServicesSunat::GET_STATUS_CDR : ServicesSunat::GET_STATUS;
    
    $this->setService($service);
  }
}
