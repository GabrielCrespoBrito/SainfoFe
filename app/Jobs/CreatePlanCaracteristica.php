<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use App\Models\Suscripcion\PlanDuracion;
use App\Models\Suscripcion\Caracteristica;

class CreatePlanCaracteristica
{
  public $planDuracion;
  public $dataCaracteristica;

  public $data;

  public function __construct(PlanDuracion $planDuracion, array $data)
  {
    $this->planDuracion = $planDuracion;
    $this->data = $data;
  }

  public function setDataCaracteristica($parent_id = null)
  {
    $this->dataCaracteristica = [
      'codigo' => null,
      'parent_id' => $parent_id,
      'nombre' => $this->data['nombre'],
      'adicional' => $this->data['adicional'],
      'tipo' => Caracteristica::RESTRICCION,
      'reset' => 0,
    ];
    
    return $this;
  }


  public function getDataCaracteristica()
  {
    return $this->dataCaracteristica;
  }

  public function createCaracteristica($plan_id, $setCaracteristicaData = false)
  {
    $caracteristica = Caracteristica::create($this->dataCaracteristica);

    if($setCaracteristicaData ){
      $this->setDataCaracteristica($caracteristica->id);
    }

    $caracteristica->plan_caracteristica()->create([
      'caracteristica_id' => $caracteristica->id,
      'plan_id' => $plan_id,
    ]);
  }

  public function handle()
  {
    $this->setDataCaracteristica();
    $this->createCaracteristica($this->planDuracion->id, true);
    $this->createCaracteristicaChilds();
  }

  public function createCaracteristicaChilds()
  {
    if (!$this->planDuracion->isMaestro()) {
      return;
    }

    $plan_childs = $this->planDuracion->planes_childs;

    foreach ($plan_childs as $plan) {
      $this->createCaracteristica($plan->id);
    }
  }
}
