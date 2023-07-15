<?php

namespace App\Jobs\Test;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;

class ExecuteTask
{
  /** 
   * Tarea o codigo a ejecutar a ejecutar
  */
  protected $task;
  protected $request;

    public function __construct( $task , Request $request )
    {
      $this->task = $task;
      $this->request = $request;
        //
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        //
    }


    return response()
    {

    }
}
