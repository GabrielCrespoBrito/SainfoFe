<?php

namespace App\Http\Controllers\Admin;

use App\Events\PlanCaracteristicaCreate;
use App\Events\PlanCaracteristicaDelete;
use App\Events\PlanCaracteristicaUpdate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlanCaracteristicaUpdateRequest;
use App\Models\Suscripcion\Plan;
use App\Models\Suscripcion\PlanCaracteristica;
use App\Models\Suscripcion\PlanDuracion;

class PlanCaracteristicaController extends Controller
{
  public function create( Request $request, $id)
  {
    PlanDuracion::findOrfail($id)->createCaracteristica($request->all());
    cache()->forget( Plan::KEY_CACHE_PAGE );
    return response()->json(['success' => true]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Htt\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(PlanCaracteristicaUpdateRequest  $request, $id)
  {
    $plan_caracteristica = PlanCaracteristica::find($id);
    event(new PlanCaracteristicaUpdate($plan_caracteristica, $request));
    cache()->forget(Plan::KEY_CACHE_PAGE);
    return response()->json(['success' => true]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $plan_caracteristica = PlanCaracteristica::find($id);

    if ($plan_caracteristica->isConsumo()) {
      return response()->json(['message' => 'No se puede eliminar una caracteristica de sistema'], 400);
    }

    event(new PlanCaracteristicaDelete($plan_caracteristica));
    cache()->forget(Plan::KEY_CACHE_PAGE);

    return response()->json(['success' => true]);
  }
}
