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
    $unidad->User_Crea = optional(auth()->user())->usulogi;
    $unidad->User_FCrea = date('Y-m-d H:i:s');
    $unidad->User_ECrea = gethostname();
  }

  /**
   * Handle the unidad "updated" event.
   *
   * @param  \App\Unidad  $unidad
   * @return void
   */
  public function updating(Unidad $unidad)
  {
    $unidad->User_Modi = optional(auth()->user())->usulogi;
    $unidad->User_FModi = date('Y-m-d H:i:s');
    $unidad->User_EModi = gethostname();
  }
}
