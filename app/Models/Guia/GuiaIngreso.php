<?php

namespace App\Models\Guia;

use App\GuiaSalida;
use App\Models\Guia\Guia;
use App\Jobs\Guia\CreateFromToma;
use App\Models\TomaInventario\TomaInventario;
use App\Models\Guia\Traits\Scopes\GuiaIngresoScope;

class GuiaIngreso extends Guia
{
  use GuiaIngresoScope;
  
  public static function createFromToma(TomaInventario $tomaInventario)
  {
    return (new CreateFromToma($tomaInventario, GuiaSalida::INGRESO))->handle();
  }

}