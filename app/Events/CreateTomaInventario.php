<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\TomaInventario\TomaInventario;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CreateTomaInventario
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $toma_inventario;
    public $items;
    public $is_store;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( TomaInventario $toma_inventario, array $items, $is_store = true)
    {
      $this->toma_inventario = $toma_inventario;
      $this->items = $items;
      $this->is_store = $is_store;
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
