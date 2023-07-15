<?php
namespace App\Util\Sunat\Request\credentials;

use App\Empresa;


/**
 * Obtener usuario y contraseña por la base de datos 
 */

class CredentialDatabase implements CredentialInterface
{
  public $empresa;

  public function __construct( Empresa $empresa )
  {
    $this->empresa = $empresa;
  }

  public function getUsername()
  {
    return  $this->empresa->EmpLin1 . $this->empresa->FE_USUNAT;
  }

  public function getPassword()
  {
    return  $this->empresa->FE_UCLAVE;
  }  
}
