<?php

namespace App\Util\Import\Excell\Producto;

use App\Marca;
use App\UnidadProducto;
use Yajra\DataTables\Processors\DataProcessor;

class UnidadSupplier extends SupplierAbstract
{
  public function setInitData()
  {
    $this->entidadData = UnidadProducto::pluck('UnPCodi', 'UnPNomb')->toArray();
  }

  public function handle(&$dataProcess)
  {
    $dataProcess[ $this->getHeader()] = $this->entidadData[ $this->campoValue ];
  }
}