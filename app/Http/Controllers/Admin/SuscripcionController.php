<?php

namespace App\Http\Controllers\Admin;


use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Suscripcion\Suscripcion;

class SuscripcionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateDate( Request $request,  $suscripcion_id)
  {
    $fecha  = $request->fecha .  " 23:59:59";

    $suscripcion = Suscripcion::find($suscripcion_id)->updateFecha($fecha);

    $dias = rand(1,50);
    return response()->json([ 
      'success' => true ,
      'fecha' => $suscripcion->fecha_final, 
      'dias' => $suscripcion->diasRestanteNumber(),
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show( $suscripcion_id )
  {
    $suscripcion = Suscripcion::with('orden.planduracion')->find($suscripcion_id);

    return view('admin.suscripcion.show', compact('suscripcion'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function current( $empresa_id )
  {
    $empresa = Empresa::find($empresa_id);

    $suscripcion = $empresa->suscripcionActual();
    return view('admin.suscripcion.show', compact('suscripcion'));
  }
  

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
