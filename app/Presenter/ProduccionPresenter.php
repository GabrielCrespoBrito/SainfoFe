<?php

namespace App\Presenter;

use App\Models\Produccion\Produccion;

class ProduccionPresenter extends Presenter
{
  public $routeName = "produccion";

  
  public function  getReadEstado()
  {
    return [
      Produccion::ESTADO_ASIGNADO => 'Asignado',
      Produccion::ESTADO_PRODUCCION => 'Producción',
      Produccion::ESTADO_ANULAD => 'Anulado',
      Produccion::ESTADO_CULMINADO => 'Culminado',
    ][$this->model->manEsta];
  }

  public function getEstado()
  {
    $bg = [
      Produccion::ESTADO_ASIGNADO => 'bg-blue',
      Produccion::ESTADO_PRODUCCION => 'bg-aqua',
      Produccion::ESTADO_ANULAD => 'bg-red',
      Produccion::ESTADO_CULMINADO => 'bg-green',
    ][$this->model->manEsta];

    return sprintf('<span class="badge %s">%s</span>', $bg, $this->getReadEstado() );
  }

}
