<?php
namespace App\Util\Sunat\Services;


/**
 * Clase padre para resolver los diferentes proveedores  y servicios a utilizar
 * 
 */

abstract class SunatResolverService
{
  protected $communicator;

  /**
   * Set the value of communicator
   *
   * @return  self
   */ 
  public function setCommunicator($communicator)
  {
    $this->communicator = $communicator;

    return $this;
  }

  /**
   * Set the value of communicator
   *
   * @return  self
   */
  public function getCommunicator()
  {
    return $this->communicator;
  }

}
