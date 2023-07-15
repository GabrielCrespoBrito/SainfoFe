<?php

namespace App\Listeners;

use App\Jobs\UpdateVentaSendFromGuia;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatedVentaSend
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
        $guia = $event->guia;
        optional($guia->venta)->updateEnvio();
    }
}
