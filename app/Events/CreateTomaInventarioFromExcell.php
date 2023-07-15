<?php

namespace App\Events;

use App\Models\TomaInventario\TomaInventario;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateTomaInventarioFromExcell
{
  use 
  Dispatchable,
  InteractsWithSockets,
  SerializesModels;

  public $tomaInventario;
  public $request;

  public function __construct(TomaInventario $tomaInventario, $request)
  {
    $this->tomaInventario = $tomaInventario;
    $this->request = $request;
  }

  public function broadcastOn()
  {
    return new PrivateChannel('channel-name');
  }
}