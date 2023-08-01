<?php

namespace App\Jobs\Empresa;

use App\User;
use App\Empresa;
use Illuminate\Support\Facades\Log;
use App\Models\Suscripcion\OrdenPago;
use App\Models\Suscripcion\PlanDuracion;
use App\Models\Suscripcion\Caracteristica;
use App\Models\Suscripcion\PlanCaracteristica;

class CreatePlanes
{
  public $empresa;
  public $is_plan_demo;

  public function __construct( Empresa $empresa, $is_plan_demo = null)
  {
    $this->empresa = $empresa;
    $this->is_plan_demo = $is_plan_demo;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $planes_duracion = PlanDuracion::with('plan')->where('tipo', PlanDuracion::TIPO_MAESTRO)->get();
    $empresa_id = $this->empresa->id();
    $empresa_plan_demo = $this->is_plan_demo ?? $this->empresa->isPlanDemo();

    $user_owner_id = optional($this->empresa->userOwner())->id() ?? User::ID_ADMIN;
    $needRegisterSuscription = true;

    foreach ($planes_duracion as $plan_duracion) {
      //
      $plan_duracion_empresa = $this->copyPlanDuracion($plan_duracion, $empresa_id);
      $plan_duracion_is_demo = $plan_duracion->isPlanDemo();
      $plan_duracion_is_pro = $plan_duracion->isPlanPro12Meses();

      $this->copyPlanCaracteristicas($plan_duracion,  $plan_duracion_empresa->id);
      
      if ($needRegisterSuscription) {


        if (($empresa_plan_demo  && $plan_duracion_is_demo) || (!$empresa_plan_demo && $plan_duracion_is_pro)) {

          $orden_pago = OrdenPago::createFromPlanDuracion($plan_duracion_empresa, $empresa_id, $user_owner_id, true);
          $orden_pago->createSuscripcion();
          $needRegisterSuscription = false;
        }


      }
    }
  }

  public function copyPlanDuracion($plan_duracion, $empresa_id)
  {
    $data = $plan_duracion->only('codigo', 'plan_id', 'duracion_id', 'descuento_porc', 'descuento_value', 'base', 'igv', 'total');
    $data['tipo'] = PlanDuracion::TIPO_EMPRESA;
    $data['empresa_id'] = $empresa_id;
    $data['update_by_parent'] = PlanDuracion::UPDATE_PARENT;
    $data['parent_id'] = $plan_duracion->id;
    return PlanDuracion::create($data);
  }



  public function copyPlanCaracteristicas($plan_duracion, $plan_duracion_empresa_id)
  {
    foreach ($plan_duracion->caracteristicas as $plan_caracteristica) {

      // Copiar Caracteristica
      $caracteristica = $plan_caracteristica->caracteristica;
      $data = $caracteristica->only('codigo', 'nombre', 'value', 'adicional', 'primary', 'active', 'tipo', 'reset');
      $data['parent_id'] = $caracteristica->id;
      $caracteristica_plan_duracion_empresa = Caracteristica::create($data);

      // Copiar Plan Caracteristica
      $data = $plan_caracteristica->only('value');
      $data['caracteristica_id'] = $caracteristica_plan_duracion_empresa->id;
      $data['plan_id'] = $plan_duracion_empresa_id;
      PlanCaracteristica::create($data);
    }
  }


}
