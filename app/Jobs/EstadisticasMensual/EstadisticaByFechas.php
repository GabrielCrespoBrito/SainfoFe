<?php

namespace App\Jobs\EstadisticasMensual;

class EstadisticaByFechas 
{
  public $fecha_desde;
  public $fecha_hasta;

    public function __construct($fecha_desde, $fecha_hasta)
    {
      $this->fecha_desde = $fecha_desde;
      $this->fecha_hasta = $fecha_hasta;
    }

    public function handle()
    {
    }

}
