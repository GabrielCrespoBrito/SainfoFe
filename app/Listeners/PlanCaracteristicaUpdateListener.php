<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Models\Suscripcion\PlanCaracteristica;

class PlanCaracteristicaUpdateListener
{

  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
  }


  /**
   * Handle the event.
   *
   * @param  object  $event
   * @return void
   */
  public function handle($event)
  {
    // Actualizar el plan actual
    $event->plan_caracteristica->updateAll($event->request);
  }
}
