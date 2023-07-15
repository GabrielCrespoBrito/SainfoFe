<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatedSerieDocumento
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $document;

    public function __construct( $document = null )
    {
        // $this->document = $document;
    }

    /**
     * Handle the event.
     *
     * @param  InvoiceCreated  $event
     * @return void
     */
    public function handle(InvoiceCreated $event)
    {
    }
}
