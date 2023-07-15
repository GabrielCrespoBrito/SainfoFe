<?php

namespace App\Http\Controllers\Suscripcion;

use Illuminate\Http\Request;
use App\Models\Suscripcion\Plan;
use App\Http\Controllers\Controller;
use App\Models\Suscripcion\PlanDuracion;

class PlanController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $empresa = get_empresa();
    $empresa_id = $empresa->id();
    $planes = Plan::all();
    $suscripcion = $empresa->suscripcionActual();
    $planduracion = $suscripcion->orden->planduracion;
    $plan_current = $planduracion->plan;
    $nombrePlan = $planduracion->nombreCompleto();
    return view('planes.index', compact('planes', 'suscripcion', 'plan_current', 'nombrePlan'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function confirm($id)
  {
    $plan_duracion = PlanDuracion::with('plan', 'duracion')->findOrfail($id);
    $condiciones = config('app.terminos_condiciones');
    // dd( $condiciones );
    return view('planes.confirm', compact('plan_duracion', 'condiciones'));
  }
}
