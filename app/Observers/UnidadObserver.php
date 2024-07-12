<?php

namespace App\Observers;

use App\Unidad;

class UnidadObserver
{
  /**
   * Handle the unidad "created" event.
   *
   * @param  \App\Unidad  $unidad
   * @return void
   */
  public function creating(Unidad $unidad)
  {
    $auditValues = auditValues();

    $unidad->User_Crea = $auditValues->user;
    $unidad->User_FCrea = $auditValues->fecha;
    $unidad->User_ECrea = $auditValues->equipo;
  }

  /**
   * Handle the unidad "updated" event.
   *
   * @param  \App\Unidad  $unidad
   * @return void
   */
  public function updating(Unidad $unidad)
  {
    $auditValues = auditValues();

    $unidad->User_Modi = $auditValues->user;
    $unidad->User_FModi = $auditValues->fecha;
    $unidad->User_EModi = $auditValues->equipo;
  }
}
