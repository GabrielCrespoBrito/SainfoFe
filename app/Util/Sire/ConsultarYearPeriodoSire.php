<?php

namespace App\Util\Sire;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class ConsultarYearPeriodoSire extends SireApi
{
  const URL = "https://api-sire.sunat.gob.pe/v1/contribuyente/migeigv/libros/rvierce/padron/web/omisos/%s/periodos";

  public function getUrl()
  {
    return sprintf(self::URL, $this->parameters->periodo); 
  }
}