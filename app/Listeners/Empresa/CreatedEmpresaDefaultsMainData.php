<?php

namespace App\Listeners\Empresa;

use App\Local;
use App\Periodo;
use App\EmpresaOpcion;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatedEmpresaDefaultsMainData
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
        # Empresa 
        EmpresaOpcion::createDefault($event->empresa->empcodi);
        # Local
        Local::createLocalDefault($event->empresa->empcodi , $event->empresa->EmpLin2, $event->empresa->EmpLin4);
        # Crear periodo
        Periodo::createDefault($event->empresa->empcodi );
    }
}
