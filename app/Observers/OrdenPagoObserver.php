<?php

namespace App\Observers;

use App\Models\Suscripcion\OrdenPago;


class OrdenPagoObserver
{
    /**
     * Handle the orden pago "created" event.
     *
     * @param  \App\OrdenPago  $ordenPago
     * @return void
     */
    public function created(OrdenPago $ordenPago)
    {
        $ordenPago->uuid = agregar_ceros($ordenPago->id,6,0);
        $ordenPago->save();
    }

    /**
     * Handle the orden pago "updated" event.
     *
     * @param  \App\OrdenPago  $ordenPago
     * @return void
     */
    public function updated(OrdenPago $ordenPago)
    {
        //
    }

    /**
     * Handle the orden pago "deleted" event.
     *
     * @param  \App\OrdenPago  $ordenPago
     * @return void
     */
    public function deleted(OrdenPago $ordenPago)
    {
        //
    }

    /**
     * Handle the orden pago "restored" event.
     *
     * @param  \App\OrdenPago  $ordenPago
     * @return void
     */
    public function restored(OrdenPago $ordenPago)
    {
        //
    }

    /**
     * Handle the orden pago "force deleted" event.
     *
     * @param  \App\OrdenPago  $ordenPago
     * @return void
     */
    public function forceDeleted(OrdenPago $ordenPago)
    {
        //
    }
}
