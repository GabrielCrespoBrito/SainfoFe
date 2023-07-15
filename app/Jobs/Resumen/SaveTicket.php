<?php

namespace App\Jobs\Resumen;

use App\Resumen;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveTicket
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Resumen $resumen, $ticket)
    {
        $this->resumen = $resumen;
        $this->ticket = $ticket;
        //
    }

    public function handle()
    {
        $this->resumen->updateTicket($this->ticket);
    }
}
