<?php

namespace App\Util\Import\Excell\Producto;

class CodigoSupplier extends SupplierAbstract
{
  public function setInitData()
  {
  }

  /**
   * @TODO, implementar una soluciòn que genere un codigo tomando en cuenta los datos del producto
   * 
   * 
   */
  public function handle(&$dataProcess)
  {
    $dataProcess[$this->getHeader('codigo_unico')] = trim($this->campoValue);
  }
}
