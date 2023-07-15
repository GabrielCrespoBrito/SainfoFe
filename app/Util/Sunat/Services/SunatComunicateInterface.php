<?php

namespace App\Util\Sunat\Services;

interface SunatComunicateInterface 
{
  /**
   * Comunicación con el servicio
   * 
   * @return void
   * */  
  public function communicate();

  /**
   * Respuesta de la comunicación
   * 
   * @return array
   * */
  public function getResponse();




}
