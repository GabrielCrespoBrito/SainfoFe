<?php

namespace App\Repositories;

use App\TipoIgv;
use App\TipoPago;

class TiposIGVRepository extends RepositoryBase
{
  public $prefix_key = "TIPO_IGV";

  public function __construct(TipoIgv $model)
  {
    parent::__construct($model);
  }
}
