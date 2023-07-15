<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatedPlan
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
      $plan = $event->plan;

      $data = $event->request->only(
        'codigo',
        'duracion_id',
        'base',
        'igv',
        'total',
        'descuento_porc',
        'descuento_value',
        'update_by_parent'
      );

      $data['update_by_parent'] = $data['update_by_parent'] ?? 1;

      if($plan->update($data)){
        $plan->updateChilds();
      }

    }
}
