<?php

namespace App\Presenter;

class TomaInventarioPresenter extends Presenter
{
  public function getEstadoColumn()
  {
    return sprintf(
      '<span class="label label-big label-%s">%s</span>',
      $this->model->getColorEstado(),
      $this->model->getNombreEstado()
    );
  }

}
