<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class CompraDeleting
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $compra; 

    public function __construct($compra)
    {
        $this->compra = $compra;
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
