<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use App\Models\Suscripcion\PlanCaracteristica;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlanCaracteristicaDelete
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $plan_caracteristica;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PlanCaracteristica $plan_caracteristica)
    {
      $this->plan_caracteristica = $plan_caracteristica;
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
