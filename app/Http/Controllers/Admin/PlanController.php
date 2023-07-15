<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use App\Events\PlanHasUpdate;
use App\Events\PlanUpdate;
use App\Events\PlanUpdateEvent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlanUpdateRequest;
use App\Models\Suscripcion\Duracion;
use App\Models\Suscripcion\PlanDuracion;

class PlanController extends Controller
{
  public function index( Request $request )
  {
    $empresa_id = $request->input('empresa_id', null);
    $showEmpresa = true;
    $empresas = Empresa::formatList($empresa_id);
    return view('admin.planes.index', compact('empresas', 'showEmpresa'));
  }

  public function search( Request $request )
  {
    $tipo = $request->tipo;
    $busqueda = PlanDuracion::where('tipo', $tipo );

    if( $tipo == PlanDuracion::TIPO_EMPRESA ){
      $busqueda = PlanDuracion::where('empresa_id', $request->empresa_id);
    }

    return DataTables::of($busqueda)
      ->addColumn('link', 'admin.planes.partials.column_link')
      ->addColumn('empresa', 'admin.planes.partials.column_empresa')
      ->addColumn('accion', 'admin.planes.partials.column_accion')
      ->rawColumns(['link',  'empresa', 'accion'])
      ->make(true);
  }


  public function edit(Request $request, $id)
  {
    $plan = PlanDuracion::with('caracteristicas.caracteristica')->find($id);
    $duraciones = Duracion::all();
    $igv_porc = config('app.parametros.igv');
    return view('admin.planes.edit', compact( 'plan','duraciones', 'igv_porc' ));
  }

  public function update( PlanUpdateRequest $request, $id)
  {
    $plan = PlanDuracion::find($id);
    event(new PlanUpdateEvent($plan, $request));
    return response()->json(['success' => true ]);
  }  
}