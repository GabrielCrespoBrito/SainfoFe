<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait InteractWithFechas
{
  public function getDiaFecha($field)
  {
    $fecha = new Carbon($this->field);
    return $fecha->getDays();
  }
}
