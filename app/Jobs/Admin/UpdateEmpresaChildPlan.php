<?php

namespace App\Jobs\Admin;

use App\Models\Suscripcion\PlanDuracion;

class UpdateEmpresaChildPlan
{

    public $planduracion;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct( PlanDuracion $planduracion)
    {
      $this->planduracion = $planduracion;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $planes = $this->planduracion->planes_childs;
      
      foreach ($planes as $plan) {
        $plan->updateFromParent($this->planduracion);
      }

    }
}
