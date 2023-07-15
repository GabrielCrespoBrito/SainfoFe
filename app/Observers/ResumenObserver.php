<?php

namespace App\Observers;

use App\Resumen;

class ResumenObserver
{
    public function creating(Resumen $resumen)
    {
        $resumen->setDates();
        $resumen->fillNume();
    }

    /**
     * Handle the resumen "created" event.
     *
     * @param  \App\Resumen  $resumen
     * @return void
     */
    public function created(Resumen $resumen)
    {
    }

    /**
     * Handle the resumen "updated" event.
     *
     * @param  \App\Resumen  $resumen
     * @return void
     */
    public function updated(Resumen $resumen)
    {
        //
    }

    /**
     * Handle the resumen "deleted" event.
     *
     * @param  \App\Resumen  $resumen
     * @return void
     */
    public function deleted(Resumen $resumen)
    {
        //
    }

    /**
     * Handle the resumen "restored" event.
     *
     * @param  \App\Resumen  $resumen
     * @return void
     */
    public function restored(Resumen $resumen)
    {
        //
    }

    /**
     * Handle the resumen "force deleted" event.
     *
     * @param  \App\Resumen  $resumen
     * @return void
     */
    public function forceDeleted(Resumen $resumen)
    {
        //
    }
}
