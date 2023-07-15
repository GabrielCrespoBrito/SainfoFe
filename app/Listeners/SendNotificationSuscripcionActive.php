<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\OrdenPagoHasProcess;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationSuscripcionActive
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  object  $event
   * @return void
   */
  public function handle($event)
  {
    $empresa = $event->orden_pago->empresa;
    $suscripcion = $event->orden_pago->suscripcion;
    try {
      $empresa->userOwner()->notify(new OrdenPagoHasProcess($event->orden_pago, $empresa));
    } catch (\Throwable $th) {
    }
  }
}
