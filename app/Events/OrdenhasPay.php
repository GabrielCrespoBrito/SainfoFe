<?php

namespace App\Events;

use App\Models\Suscripcion\OrdenPago;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OrdenhasPay
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orden_pago;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( OrdenPago $orden_pago)
    {
        $this->orden_pago = $orden_pago;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
