<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\OrdenPagoCreatedNotification;

class SendOrderNotification
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
      try {
        $event->orden_pago->user->notify(new OrdenPagoCreatedNotification( $event->orden_pago ));
      } catch (\Throwable $th) {
        Log::info(sprintf('@Error App\Listeners\SendOrderNotification'));
      }
    }
}
