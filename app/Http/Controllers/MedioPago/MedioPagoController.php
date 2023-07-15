<?php

namespace App\Http\Controllers\MedioPago;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MedioPago\MedioPago;
use App\Http\Controllers\Controller;
use App\Repositories\MedioPagoRepository;

class MedioPagoController extends Controller
{
  public $model;

  public function __construct()
  {
    $this->model = new MedioPago();
    $this->middleware(p_midd('R_MEDIOPAGO', 'R_UTILITARIO'));
  }
  public function index()
  {
    $medios_pagos = MedioPago::with('tipo_pago_parent')->get();
    return view('medios_pagos.index', compact('medios_pagos'));
  }

  public function changeStatus($id)
  {
    $medios_pagos = MedioPago::all();
    $medio_pago = MedioPago::find($id);

    if ($medio_pago->isUso() && $medio_pago->isDefault() ) {      
      noti()->warning('No se puede Desactivar el Medio de Pago por Defecto, Cambie a otro Medio de Pago por Defecto, para poder Desactivar este');
      return redirect()->back();
    }


    $activos = $medios_pagos->where('uso', MedioPago::ESTADO_USO);

    if( $activos->count() == 1 ){
      if($medio_pago->id == $activos->first()->id){
        noti()->warning('Tiene que haber al menos un medio de de pago habilitado');
        return redirect()->back();
      }
    }

    $medio_pago->toggleEstado();
    (new MedioPagoRepository(new MedioPago(), empcodi()))->clearCache('all');

    noti()->success('Acción Exitosa');
    return redirect()->route('medios_pagos.index');
  }

  public function setDefault($id)
  {
    $medio_pago = MedioPago::find($id);

    if( $medio_pago->isNoUso() ){
      noti()->warning('No se puede poner como medio de pago por defecto, un medio Desactivado');
      return redirect()->back();
    }

    $medios_pagos = MedioPago::query()->update(['default' => MedioPago::NO_DEFAULT]);
    $medio_pago->update(['default' => MedioPago::DEFAULT]);
    (new MedioPagoRepository(new MedioPago(), empcodi()))->clearCache('all');
    noti()->success('Acción Exitosa');
    return redirect()->route('medios_pagos.index');
  }

}
