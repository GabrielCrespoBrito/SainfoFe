<?php

namespace App\Util\Sunat\Request\credentials;

interface CredentialInterface
{
  /**
   * Obtener el usuario y la clave sol del usuario para la comunicación la sunat
   * 
   * @return string
   */
  public function getUsername();
  public function getPassword();
}
