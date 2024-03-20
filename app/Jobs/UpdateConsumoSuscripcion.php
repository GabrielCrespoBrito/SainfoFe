<?php

namespace App\Jobs;

use App\Models\Suscripcion\Suscripcion;

class UpdateConsumoSuscripcion
{
  public $suscripcion;
  public $caracteristica;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Suscripcion $suscripcion, $caracteristica )
    {
      $this->suscripcion = $suscripcion;
      $this->caracteristica = $caracteristica; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $uso = $this->suscripcion->getUso($this->caracteristica);
      $uso->updateUso();
  }
}
