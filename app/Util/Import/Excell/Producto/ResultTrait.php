<?php

namespace App\Util\Import\Excell\Producto;

trait ResultTrait
{
  protected $result = [
    'success' => true,
    'errors' => [],
  ];

  public function getResult()
  {
    return (object) $this->result;
  }

  public function addError( $errors )
  {
    foreach ((array) $errors as $error) {
      $this->result['errors'][] = $error;
    }

   return $this->result['success'] = false;
  }
}
