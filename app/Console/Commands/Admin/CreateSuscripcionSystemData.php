<?php

namespace App\Console\Commands\Admin;

use App\User;
use App\Empresa;
use Illuminate\Console\Command;
use App\Models\Suscripcion\OrdenPago;
use App\Models\Suscripcion\PlanDuracion;
use App\Models\Suscripcion\Caracteristica;
use App\Models\Suscripcion\PlanCaracteristica;

class CreateSuscripcionSystemData extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:create_suscripcion_system';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Crear Sistema de Suscripción para las empresas registradas';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }



  public function createPlanesEmpresa()
  {
    // $empresas = Empresa::activas()->ambienteProduccion()->get();
    $empresas = Empresa::activas()->get();
    foreach ($empresas as $empresa) {
      $empresa->createPlanes();
    }
  }

  public function handle()
  {
    $this->createPlanesEmpresa();
    // $this->copyPlanDefault();
  }

  public function copyPlanDefault()
  {
    $plan_caracteristicas = PlanCaracteristica::all();
    $plan_id = 2;
    for( $i = 0;  $i < 6; $i++ ){

      foreach ( $plan_caracteristicas as $plan_caracteristica ) {        
        // Caracteristica
        $data = $plan_caracteristica->caracteristica->toArray();
        // $data['parent_id'] =  $data['id'];
        $data['parent_id'] = null;
        $caracteristicaCopy = Caracteristica::create($data);

        // Plan Caracteristica Copy
        $data = $plan_caracteristica->toArray();
        $data['plan_id'] = $plan_id;
        $data['caracteristica_id'] = $caracteristicaCopy->id;
        PlanCaracteristica::create($data);
      }
      $plan_id++;
    }
  }
}
