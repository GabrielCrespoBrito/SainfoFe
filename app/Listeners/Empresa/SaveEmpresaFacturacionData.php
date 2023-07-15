<?php

namespace App\Listeners\Empresa;

use App\Empresa;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveEmpresaFacturacionData
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
        $event->empresa->update([
            'FE_CLAVE' =>  $event->request->cert_password,
            'FE_USUNAT' =>  $event->request->usuario_sol,
            'FE_UCLAVE' =>  $event->request->clave_sol,
            'OPC'  => 1,
            'fe_envfact'  => 1,
            'fe_envbole'  => 1,
            'fe_envncre'  => 1,
            'fe_envndebi' =>  1,
            'fe_servicio' => Empresa::PROVEEDOR_SUNAT,
            'fe_ambiente' => Empresa::PRODUCCION,
            'fe_version' => "2.1",
        ]);

     
    }
}
