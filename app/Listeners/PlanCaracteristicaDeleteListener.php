<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Models\Suscripcion\PlanCaracteristica;

class PlanCaracteristicaDeleteListener
{
  public $plan_caracteristica;

  public function __construct()
  {
  }

  public function handle($event)
  {
    $event->plan_caracteristica->deleteAll();
  }
}
