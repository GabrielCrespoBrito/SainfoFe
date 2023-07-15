<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\OrdenPagoCreatedNotification;

class CreatedAndStoreOrdenPdf
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
      // tulsa
      try {
        $data =  $event->orden_pago->dataPDF();
        $pdf = \PDF::loadView( 'orden_pago.pdf' , $data );
        $pdf->setPaper('a4');
        $fileName = $event->orden_pago->fileName();
        Storage::disk('s3')->put($event->orden_pago->empresa->ruc() . '/ordenes_pago/' . $fileName, $pdf->output());
        $event->orden_pago->user->notify(new OrdenPagoCreatedNotification($event->orden_pago));       
      } catch (\Throwable $th) {
        //throw $th;
      }


    }
}
