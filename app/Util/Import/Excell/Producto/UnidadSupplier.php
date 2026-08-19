<?php

namespace App\Util\Import\Excell\Producto;

use App\Marca;
use App\UnidadProducto;
use Yajra\DataTables\Processors\DataProcessor;

class UnidadSupplier extends SupplierAbstract
{
  public function setInitData()
  {
    $this->entidadData = UnidadProducto::pluck('UnPNomb', 'UnPCodi')->toArray();
  }

  public function handle(&$dataProcess)
  {
    $dataProcess[ $this->getHeader()] = $this->campoValue;
  }
}