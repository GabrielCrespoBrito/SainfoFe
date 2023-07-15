<?php

namespace App\Events\Monitoreo\Empresa;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\ModuloMonitoreo\Empresa\Empresa;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmpresaMonitoreoHasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $empresa;
    public $request;

    public function __construct(Empresa $empresa, $request)
    {
        $this->empresa = $empresa;
        $this->request = $request;
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
