<?php

namespace App\Repositories;

use App\SettingSystem;

class SettingSystemRepository extends RepositoryBase
{
  public $prefix_key = "SYSTEM_REPOSITORY";

  public function __construct(SettingSystem $model)
  {
    parent::__construct($model);
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
    return cache()->rememberForever($key, function () use($field,$value,$model)  {
      return $model->where($field,$value)->get();
    });
  }

}
