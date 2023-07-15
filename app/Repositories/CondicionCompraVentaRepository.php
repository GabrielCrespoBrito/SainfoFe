<?php

namespace App\Repositories;

use App\CondicionVenta;
use Illuminate\Database\Eloquent\Model;

class CondicionCompraVentaRepository extends RepositoryBase
{
  public $prefix_key = "CONDICION_COMPRA_VENTA";
  public $cacheKeys = [
    'find.01',
    'find.02',
    'find.03',
  ];

  public function __construct(CondicionVenta $model, $empcodi = null)
  {
    parent::__construct($model, $empcodi);
  }
}