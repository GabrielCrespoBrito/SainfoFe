<?php

namespace App\Jobs;

use App\GuiaSalida;
use App\Models\Guia\GuiaIngreso;
use App\Models\Produccion\Produccion;
use App\Jobs\Produccion\CreateDetalleFromProduccionDetalle;

class CreateMovs
{
  public $produccion;
  public $guiaSalida = null;
  public $guiaIngreso = null;

  public function __construct(Produccion $produccion)
  {
    $this->produccion = $produccion;
  }

  public function createGuiaSalida()
  {
    $this->guiaSalida  = GuiaSalida::createFromProduccionManual($this->produccion, false);
  }

  public function createGuiaIngreso()
  {
    $this->guiaIngreso = GuiaIngreso::createFromProduccionManual($this->produccion);
  }

  public function registersDetalle()
  {
    (new CreateDetalleFromProduccionDetalle(
      $this->produccion,
      $this->guiaSalida, 
      $this->guiaIngreso))
      ->handle();
  }

  public function handle()
  {
    $this->createGuiaIngreso();
    $this->createGuiaSalida();
    $this->registersDetalle();
  }
}
