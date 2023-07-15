<?php
namespace App\Util\Sunat\Services\SunatSendSummary;


use App\Util\Sunat\Services\SunatCommunicate;

/**
 * Clase para envio del resumenes 
 * 
 */
abstract class SendSummary  extends SunatCommunicate
{
  public function __construct()
  {
    $this->setService('sendSummary');
  }  
}
