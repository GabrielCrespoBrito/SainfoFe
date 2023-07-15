<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrdenPagoHasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orden_pago;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orden_pago)
    {
        $this->orden_pago = $orden_pago;
    }
}
