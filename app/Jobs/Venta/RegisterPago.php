<?php

namespace App\Jobs\Venta;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RegisterPago implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $documento;
  public $data;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($documento, $data)
  {
    $this->documento = $documento;
    $this->data = $data;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $efectivo = 0;
    $vuelto = 0;

    $this->documento->VtaEfectivo = $efectivo;
    $this->documento->VtaVuelto = $vuelto;
    $this->documento->save();
  }
}
