<?php

namespace App\Http\Controllers\Admin;

use App\TipoPago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\Admin\TipoPagoRequest;
use App\Console\Commands\SincronizarMediosPagos;

class TipoPagoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $tipos_pagos = TipoPago::all();
    return view('admin.tipos_pagos.index', compact('tipos_pagos') );
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $route = route('admin.tipo_pago.store');
    $create = true;
    $model = new TipoPago();
    return view('admin.tipos_pagos.create', compact('route','create','model'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(TipoPagoRequest $request)
  {
    $data = $request->except('_token');
    $data['TpgCodi'] = agregar_ceros( ((int) TipoPago::max('TpgCodi')) , 2, 1);  
    $tp = TipoPago::create($data);
    Artisan::call('system_task:sincronizar_medios_pagos', ['tipo_pago' => $data['TpgCodi']]);
    noti()->success('Tipo de Pago Registrado Exitosamente');
    return redirect()->route('admin.tipo_pago.index');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $model = TipoPago::findOrfail($id);
    $route = route('admin.tipo_pago.update', $id);
    $create = false;
    return view('admin.tipos_pagos.edit', compact('route', 'create', 'model'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(TipoPagoRequest $request, $id)
  {
    TipoPago::find($id)->update($request->only('TpgNomb', 'TdoBanc'));    
    noti()->success('Tipo de Pago Modificado Exitosamente');
    return redirect()->route('admin.tipo_pago.index');
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
