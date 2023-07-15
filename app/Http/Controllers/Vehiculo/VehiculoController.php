<?php

namespace App\Http\Controllers\Vehiculo;

use App\Vehiculo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculo\VehiculoRequest;

class VehiculoController extends Controller
{
  public $model;

  public function __construct()
  {
    $this->model = new Vehiculo();
    $this->middleware(p_midd('A_INDEX', 'R_TRANSPORTISTA'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_TRANSPORTISTA'))->only('create');
    $this->middleware(p_midd('A_EDIT', 'R_TRANSPORTISTA'))->only('edit');
    $this->middleware(p_midd('A_DELETE', 'R_TRANSPORTISTA'))->only('destroy');
  }

  /**
   * Busqueda por ajax
   *
   * @param Request $request
   * @return void
   */
  public function search(Request $request)
  {
    $term = $request->data;

    if (empty($term)) {
      $models = $this->model->get();
    } else {
      $models = $this->model->descripcion($term)->get();
    }

    $data = [];

    foreach ($models as $model) {
      $text = $model->descripcionComplete();
      $data[] = ['id' => $model->id, 'text' => $text];
    }

    return \Response::json($data);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $vehiculos = $this->model->get();

    return view('vehiculo.index', [
      'vehiculos' => $vehiculos
    ]);
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('vehiculo.create', [
      'model' => $this->model
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(VehiculoRequest $request)
  {
    $this->model->create($request->only('VehPlac', 'VehMarc', 'VehInsc'));
    notificacion('Accion exitosa', 'Se ha creado satisfactoriamente el recurso');
    return redirect()->route('vehiculo.index');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $model = $this->model->findOrfail($id);
    return view('vehiculo.edit', [
      'model' => $model
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(VehiculoRequest $request, $id)
  {
    $model = $this->model->findOrfail($id);
    $model->update($request->only('VehPlac', 'VehMarc', 'VehInsc'));
    notificacion('Accion exitosa', 'Se ha actualizado satisfactoriamente el recurso');
    return redirect()->route('vehiculo.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $model = $this->model->findOrfail($id);
    // $model = Empresa Transportista::findOrfail($id);
    if ($model->guias->count()) {
      noti()->error('Accion cancelada', 'No se puede eliminar este Vehiculo porque tiene guias asociadas');
      return redirect()->route('vehiculo.index');
    }


    $model->delete();
    notificacion('Accion exitosa', 'Se ha eliminado satisfactoriamente el recurso');
    return redirect()->route('vehiculo.index');
  }
}
