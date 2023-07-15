<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\OrdenPagoHasProcess;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationOrdenPayHasProcess
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
        $event->orden_pago->user->notify( new OrdenPagoHasProcess($event->orden_pago) );
        //
    }
}
