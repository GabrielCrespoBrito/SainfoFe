<?php

namespace App\Repositories;

use App\TipoPago;

class TipoPagoRepository implements RepositoryInterface
{
  const CACHE_PREFIX_KEY = "TIPO_PAGO";

    public function __construct( TipoPago $tipopago )
    {
      $this->model = $tipopago;
    }

    public function getKey($key)
    {
      $key = strtoupper($key);
      return self::CACHE_PREFIX_KEY . ".$key";
    }

    public function all()
    {
      $key = $this->getKey('all');
      return cache()->rememberForever($key, function() use($key){
        return $this->model->get();
      });
    }

    public function create(array  $data)
    {
    }

    public function update(array $data, $id)
    {
    }

    public function delete($id)
    {
    }

    public function find($id)
    {
    }
}
