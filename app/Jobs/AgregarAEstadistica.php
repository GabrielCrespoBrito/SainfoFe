<?php

namespace App\Jobs;

use App\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AgregarAEstadistica
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Venta $venta )
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
