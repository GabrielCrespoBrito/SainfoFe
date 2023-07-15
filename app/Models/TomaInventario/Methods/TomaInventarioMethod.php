<?php
namespace App\Models\TomaInventario\Methods;

use App\Jobs\TomaInventario\CreateGuias;
use App\Models\TomaInventario\TomaInventario;

trait TomaInventarioMethod
{
  public function IsPendiente()
  {
    return $this->InvEsta == TomaInventario::ESTADO_PENDIENTE;
  }

  public function IsCerrada()
  {
    return $this->InvEsta == TomaInventario::ESTADO_CERRADO;
  }

  public function getColorEstado()
  {
    return $this->isPendiente() ? 'warning' : 'success';
  }

  public function getNombreEstado()
  {
    return $this->isPendiente() ? 'Pendiente' : 'Cerrado';
  }

  public function getNextName()
  {
    return 'INVENTARIO ' . agregar_ceros(TomaInventario::max('InvCodi'), 6 , 1);
  }

  public function createGuias()
  {
    (new CreateGuias($this))->handle();
  }

  public function getNamePDF()
  {
    return "toma_inventario_" . $this->InvCodi . '.pdf';
  }
}