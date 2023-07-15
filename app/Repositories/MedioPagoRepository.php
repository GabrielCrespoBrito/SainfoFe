<?php

namespace App\Repositories;

class MedioPagoRepository extends RepositoryBase
{
  const CACHE_PREFIX_KEY = "MEDIO_PAGO";

  public function all()
  {
    $key = $this->getKey('all');
    return cache()->rememberForever($key, function () {
      return $this->model->get()->load('tipo_pago_parent');
    });
  }
}
