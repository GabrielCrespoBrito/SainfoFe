<?php

namespace App\Models\Suscripcion;

use App\Jobs\Admin\UpdateEmpresaChildPlan;
use App\Jobs\CreatePlanCaracteristica;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class PlanDuracion extends Model
{
	use UsesSystemConnection;

  const TIPO_MAESTRO = "maestro";
  const TIPO_EMPRESA = "empresa";
  const NOT_UPDATE_PARENT = 0;
  const UPDATE_PARENT = 1;

  protected $fillable = [
    'codigo',
    'plan_id',
    'duracion_id',
    'descuento_porc',
    'descuento_value',
    'base',
    'igv',
    'total',
    'tipo',
    'empresa_id',
    'update_by_parent',
    'parent_id',
  ];


  protected $table = "suscripcion_system_planes_duraciones";

	public function plan()
	{
		return $this->belongsTo(Plan::class, 'plan_id');
	}

  public function caracteristicas()
  {
    return $this->hasMany(PlanCaracteristica::class, 'plan_id');
  }

	public function duracion()
	{
		return $this->belongsTo(Duracion::class, 'duracion_id');
	}

	public function nombreCompleto()
	{
		return "Plan {$this->plan->nombre} {$this->duracion->nombre}";
	}

	public function isDiario()
	{
		return $this->duracion->isDiario();
	}

  public function isMaestro()
  {
    return $this->tipo == self::TIPO_MAESTRO;
  }

  public function isEmpresa()
  {
    return $this->tipo == self::TIPO_EMPRESA;
  }

  public function planes_childs()
  {
    return $this->hasMany(PlanDuracion::class, 'parent_id', 'id' )->where('update_by_parent', PlanDuracion::UPDATE_PARENT);
  }

  public function updateByParent()
  {
    return $this->update_by_parent == PlanDuracion::UPDATE_PARENT;
  }

  /**
   * Actualizar planes
   * 
   */
  public function updateChilds()
  {
    if( $this->isMaestro() ){
      (new UpdateEmpresaChildPlan($this))->handle();
    }
  }

  /**
   * Actualizar a partir de plan padre
   * 
   */
  public function updateFromParent( PlanDuracion $plan_parent )
  {
    $this->update([
      'codigo' => $plan_parent->codigo,
      'descuento_porc' => $plan_parent->descuento_porc,
      'descuento_value' => $plan_parent->descuento_value,
      'igv' => $plan_parent->igv,
      'base' => $plan_parent->base,
      'total' => $plan_parent->total,      
    ]);
  }

	/**
	 * Obtener la fecha cuando se venceria el plan a partir del dia de ahora
	 * @return string
	 */
	public function getFechaVencimiento()
	{
		$fecha_actual = now();
		$duracion = $this->duracion;
		$duracion->isDiario();

		return $duracion->isDiario() ? $fecha_actual->addDays($duracion->duracion) :  $fecha_actual->addMonth($duracion->duracion);
	}

  public function isPlanPro()
  {
    return trim(strtolower($this->codigo)) == "plan-pro-12meses";
  }

  public function isPlanPro12Meses()
  {
    return trim(strtolower($this->codigo)) == "plan-pro-12meses";
  }

  public function isPlanDemo()
  {
    return $this->plan->isDemo();
  }

  public function createCaracteristica($data)
  {
    (new CreatePlanCaracteristica($this,$data))->handle();
  }
}
