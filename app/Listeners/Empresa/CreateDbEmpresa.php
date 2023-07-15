<?php

namespace App\Listeners\Empresa;

use Illuminate\Support\Facades\Artisan;

class CreateDbEmpresa
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
        Artisan::call( 'tenant:create' , [
            'empcodi' => $event->empresa->empcodi
        ]);

    }
}
