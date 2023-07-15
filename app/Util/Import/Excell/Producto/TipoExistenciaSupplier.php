<?php

namespace App\Util\Import\Excell\Producto;


class TipoExistenciaSupplier extends SupplierAbstract
{
  public function setInitData()
  {
    $this->entidadData =  cacheHelper('tipoexistencia.all')->pluck('TieCodi','TieNomb');
  }

  public function handle(&$dataProcess)
  {
    $dataProcess[$this->getHeader()] = $this->entidadData[$this->campoValue];
  }
}
