<?php

namespace App\Models\Notifications;

use Illuminate\Notifications\DatabaseNotification as DBN;

class DatabaseNotification extends DBN
{
  const ESTADO_LEIDA     = 'leida';
  const ESTADO_PENDIENTE = 'pendiente';

  public function readEstado()
  {
    return $this->read() ? 'Leida' : 'Pendiente';
  }

}
