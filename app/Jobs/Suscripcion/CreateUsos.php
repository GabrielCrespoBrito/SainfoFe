<?php

namespace App\Jobs\Suscripcion;

use App\Models\Suscripcion\Suscripcion;
use App\Suscripcion\SuscripcionUso;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateUsos
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $suscripcion;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Suscripcion $suscripcion)
	{
		$this->suscripcion = $suscripcion;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
    $caracteristicas = $this->suscripcion->orden->planduracion->caracteristicas;
		$suscripcion_id = $this->suscripcion->id;
		foreach ($caracteristicas as $caracteristica) {
			$limite = $caracteristica->value ?? 0;
			$data = [
				'caracteristica_id' => $caracteristica->caracteristica_id,
				'suscripcion_id' => $suscripcion_id,
				'limite' => $limite,
				'uso' => 0,
				'restante' => $limite,
			];      
			SuscripcionUso::create($data);
		}
	}
}
