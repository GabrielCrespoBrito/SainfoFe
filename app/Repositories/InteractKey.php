<?php

namespace App\Repositories;

trait InteractKey
{
  /**
   * Obtener la key unica para acceder al recurso
   *
   * @param string $key
   * @return string
   */
  public function getKey($key)
  {
    $empcodiKey = $this->empcodiKey ? "{$this->empcodiKey}." : '';

    return strtoupper(sprintf("%s.%s%s",
      $this->getPrefixKey(),
      $empcodiKey,
      $key
    ));
  }

  /**
   * Obtener el prefijo para la clave
   *
   * @return void
   */
  public function getPrefixKey()
  {
    return $this->prefix_key ?? $this->generatePrefixKey();
  }

  /**
   * Generar una clave a partir del nombre de la clase 
   * 
   * @return string
   */
  public function generatePrefixKey()
  {
    return str_replace('\\','.', get_class($this->model) );
  }

 
}
