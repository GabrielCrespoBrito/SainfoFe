<?php

namespace App\Util\Import\Excell\Producto;

class IncluyeIGVSupplier extends SupplierAbstract
{
  public function setInitData()
  {
    $this->entidadData = [
      'Si' => 1,
      'No' => 0,
    ];
  }

  public function handle(&$dataProcess)
  {
    $dataProcess[$this->getHeader()] = $this->entidadData[$this->campoValue];
  }
}
