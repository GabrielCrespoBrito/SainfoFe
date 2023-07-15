<?php

namespace App\Models\Suscripcion;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class PlanCaracteristica extends Model
{
  use UsesSystemConnection;
  
  protected $fillable = [
    'value',
    'plan_id',
    'caracteristica_id',
    'active'
  ];

  protected $table = "suscripcion_system_planes_caracteristicas";

  public function plan()
  {
    return $this->belongsTo(Plan::class, 'plan_id');
  }

  public function plan_duracion()
  {
    return $this->belongsTo(PlanDuracion::class, 'plan_id');
  }

  public function caracteristica()
  {
    return $this->belongsTo(Caracteristica::class, 'caracteristica_id');
  }

  public function isConsumo()
  {
    return $this->caracteristica->tipo === Caracteristica::CONSUMO;
  }

  public function isMaestro()
  {
    return $this->caracteristica->isMaestro();
  }

  public function deleteAll()
  {
    if ($this->isMaestro()) {
      $caracteristica_childs = $this->caracteristica->caracteristica_childs;
      foreach ($caracteristica_childs as $caracteristica_child) {
        if ($caracteristica_child->plan_caracteristica->plan_duracion->updateByParent()) {
          $caracteristica_child->plan_caracteristica->deleteComplete();
        }
      }
    }    
    $this->deleteComplete();
  }

  // $data['fixed-value-style'] = "hello word"
  public function deleteComplete()
  {        
    $this->caracteristica->delete();
    $this->delete();    
  }

  /**
   * Actualizar la caracteristica
   * @return void
   */
  public function updateAll($request)
  {
    
    if ($this->isMaestro()) {
      $caracteristica_childs = $this->caracteristica->caracteristica_childs;
      foreach ($caracteristica_childs as $caracteristica_child) {
        if ($caracteristica_child->plan_caracteristica->plan_duracion->updateByParent()) {
          $caracteristica_child->plan_caracteristica->updateComplete($request);
        }
      }
    }
    // Hay-Una-Pequeña-Fundaciòn-Styles-Marcas
    $this->updateComplete($request);
  }

  /**
   * Actualizar la caracteristica
   * @return void
   */
  public function updateComplete($request)
  {
    // Actualizar Plan Caracteristica
    $this->update([
      'value' => $request->value
    ]);

    // Actualizar Caracteristica
    $caracteristica = $this->caracteristica;

    $data = [
      'nombre' => $request->input('nombre'),
      'adicional' => $request->input('adicional'),
    ];

    $caracteristica->update($data);
  }
}
