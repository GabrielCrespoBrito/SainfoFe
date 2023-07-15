<?php

namespace App\Events\Empresa;

use App\Empresa;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CertFacturacionInfoSave
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $empresa;
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Empresa $empresa, Request $request )
    {
        $this->empresa = $empresa;
        $this->request = $request;
        //
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
