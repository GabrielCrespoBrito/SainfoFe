<?php

namespace App\Listeners\Monitoreo\Empresa;


class SaveCertificates
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
        $cersFolder = $path = $event->empresa->getFolderSave('cer');

        
        if ($event->request->has('cert_key')) {
            $event->empresa->saveCert('key' , $event->request->file('cert_key') );
        }

        if ($event->request->has('cert_cer')) {
            $event->empresa->saveCert('cer', $event->request->file('cert_cer'));
        }

        if ($event->request->has('cert_pfx')) {
            $event->empresa->saveCert('pfx', $event->request->file('cert_pfx'));
        }
		
    }
}
