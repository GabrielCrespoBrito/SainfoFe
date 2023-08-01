<?php

namespace App\Jobs\Suscripcion;

use App\Empresa;
use App\Helpers\CacheHelper;
use Illuminate\Bus\Queueable;
use App\Models\Suscripcion\OrdenPago;
use Illuminate\Queue\SerializesModels;
use App\Models\Suscripcion\Suscripcion;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class CreateSuscripcion implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $ordenPago;
  public $fechaFinal;

  /**
   *      * Create a new job instance.
   *
   * @return void
   */
  public function __construct(OrdenPago $ordenPago, $fechaFinal = null)
  {
    $this->ordenPago = $ordenPago;
    $this->fechaFinal = $fechaFinal;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $empresa = $this->ordenPago->empresa;
    
    $now = now();
    $lapso = $this->ordenPago->planduracion->duracion->duracion;

    // Si el tipo de duración es diaria, agregar dias, de lo contrario meses
    
    if( $this->fechaFinal ){
      $fecha_final = $this->fechaFinal;
    }
    else {

      if( $this->ordenPago->fecha_vencimiento ){
        $fecha_final = $this->ordenPago->fecha_vencimiento;
      }

      else {
        $fecha_final = $this->ordenPago->planduracion->isDiario() ?
        $now->copy()->addDays($lapso)->endOfDay() :
        $now->copy()->addMonths($lapso)->endOfDay();
      }
    }

    // dd( $this->fechaFinal, $fecha_final );
    // exit();

    # Crear la suscripción
    $data = [
      'orden_id' => $this->ordenPago->id,
      'empresa_id' => $this->ordenPago->empresa_id,
      'fecha_inicio' => now(),
      'fecha_final' => $fecha_final,
      'estatus' => Suscripcion::ACTIVA,
      'actual' => Suscripcion::ACTUAL
    ];

    $suscripcion = Suscripcion::create($data);
    $suscripcion->createUsos();

    $empresa->updateTiempoSuscripcion($fecha_final);
    $empresa->updateSuscripciones($suscripcion);

    $tipo_plan = $this->ordenPago->planduracion->plan->isDemo() ? Empresa::PLAN_DEMO : Empresa::PLAN_REGULAR;

    # Guardar el tipo de plan
    $empresa->saveTipoPlan($tipo_plan);

    # Limpiar el cache de la empresa
    $empresa->cleanCache();
  }
}
