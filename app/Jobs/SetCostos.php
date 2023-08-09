<?php

namespace App\Jobs;

use App\Models\Produccion\Produccion;

class SetCostos
{
  public $produccion;

  public function __construct( Produccion $produccion)
  {
    $this->produccion = $produccion;
  }

  public function handle()
  {
    //
  }
}
