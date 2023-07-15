<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

abstract class RepositoryBase implements RepositoryInterface
{
  use InteractKey;
  
  protected $model;
  protected $empcodiKey;
  protected $cacheKeys = [];

  public function __construct(Model $model, $empcodiKey = false)
  {
    $this->model = $model;
    $this->empcodiKey = $empcodiKey;
  }

  public function getCacheKeys()
  {
    return $this->cacheKeys;
  }

  public function all()
  {
    $key = $this->getKey('all');

    return cache()->rememberForever($key, function () {
      return $this->model->get();
    });
  }

  public function where($field, $value)
  {
    $key = $this->getKey("where.{$field}.{$value}");
    $model = $this->model;
    return cache()->rememberForever($key, function () use ($field, $value, $model) {
      return $model->where($field, $value)->get();
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
    $key = $this->getKey("find.{$id}");

    return Cache::rememberForever($key, function () use($id) {
      return $this->model->find($id);
    });
  }

  //@TODO Configurar despues para que pueda eliminar cualquier cache almacenado, hasta ahora solo podra eliminar Cache simples
  public function clearCache($key = null)
  {

    if( $key ){
      Cache::forget($this->getKey($key));
      return;
    }

    $keys = $this->getCacheKeys();
    foreach( $keys as $key ){
      Cache::forget($this->getKey($key));
    }
  }

}