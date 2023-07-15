<?php

namespace App\Events;

use App\Venta;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class GuiaSimplyHasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $venta;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, Venta $venta)
    {
        $this->data = $data;
        $this->venta = $venta;
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