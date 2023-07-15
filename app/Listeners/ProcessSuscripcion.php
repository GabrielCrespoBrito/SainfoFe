<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessSuscripcion
{
  public function __construct()
  {
  }

  public function handle($event)
  {
    $event->orden_pago->empresa->saveRequiredConfig();
    // $event->orden_pago->empresa->desactivarSuscripciones();
    $event->orden_pago->createSuscripcion();
  }
}
