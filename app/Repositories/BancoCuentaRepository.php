<?php

namespace App\Repositories;

use App\BancoEmpresa;

class BancoCuentaRepository extends RepositoryBase
{
  public $prefix_key = "BANCOCUENTA";

  public function __construct(BancoEmpresa $model, $empcodi = null )
  {
    parent::__construct($model, $empcodi);
  }

  public function create( $data )
  {    
    $model = $this->model;
    $data['EmpCodi'] = $this->empcodiKey;
    $model->fill($data);
    $model->fillCueCodi();
    $model->save();
  }

  public function update(array $data, $id)
  {
    $model = $this->model->findOrfail($id);
    $model->fill($data);
    if($model->isDirty()){
      $model->save(); 
    }
  }

  public function delete($id)
  {
    $model = $this->model->findOrfail($id);
    $model->delete();
  }


  public function where($field, $value)
  {
    $key = $this->getKey("where.{$field}.{$value}");
    $model = $this->model;
    return cache()->rememberForever($key, function () use ($field, $value, $model) {
      return $model->where($field, $value)->get();
    });
  }
}
