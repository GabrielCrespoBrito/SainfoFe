<?php

namespace App\Presenter;

trait InfoTrait
{
  public function getId()
  {
    return $this->model->{$this->model->getKeyName()};
  }

  public function getDescripcion()
  {
    return $this->model->{$this->model->getKeyName()};
  }
}
