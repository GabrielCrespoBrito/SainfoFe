<?php

namespace App\Util\Import\Excell\Producto;

use App\BaseImponible;

class BaseIGVSupplier extends SupplierAbstract
{
  public function setInitData()
  {
    $this->entidadData = [
      BaseImponible::GRAVADA => BaseImponible::GRAVADA,
      BaseImponible::INAFECTA => BaseImponible::INAFECTA,
      BaseImponible::INAFECTA => BaseImponible::INAFECTA,
      BaseImponible::INAFECTA => BaseImponible::INAFECTA,
    ];   
  }

  public function handle(&$dataProcess)
  {
    $dataProcess[$this->getHeader()] = $this->entidadData[$this->campoValue];
  }
}
