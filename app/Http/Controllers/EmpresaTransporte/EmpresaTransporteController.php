<?php

namespace App\Http\Controllers\EmpresaTransporte;

use App\EmpresaTransporte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaTransporte\EmpresaTransporteRequest;

class EmpresaTransporteController extends Controller
{
  public $model;

  public function __construct()
  {
    $this->model = new EmpresaTransporte();        
    $this->middleware(p_midd('A_INDEX', 'R_EMPRESATRANSPORTE'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_EMPRESATRANSPORTE'))->only('create','store');
    $this->middleware(p_midd('A_EDIT', 'R_EMPRESATRANSPORTE'))->only('edit','update');
    $this->middleware(p_midd('A_DELETE', 'R_EMPRESATRANSPORTE'))->only('destroy');

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
    $empresas_transporte = $this->model->get();
    return view('empresa_transporte.index', [
      'empresas_transporte' => $empresas_transporte
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('empresa_transporte.create', [
      'model' => $this->model
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EmpresaTransporteRequest $request)
  {
    $this->model->create($request->only('EmpNomb', 'EmpRucc', 'mtc' ));
    notificacion('Accion exitosa', 'Se ha creado satisfactoriamente el recurso');
    return redirect()->route('empresa_transporte.index');
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
    return view('empresa_transporte.edit', [
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
  public function update(EmpresaTransporteRequest $request, $id)
  {
    $model = $this->model->findOrfail($id);
    $model->update($request->only('EmpNomb', 'EmpRucc', 'mtc'));
    notificacion('Accion exitosa', 'Se ha actualizado satisfactoriamente el recurso');
    return redirect()->route('empresa_transporte.index');
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
    if ( $model->guias->count()) {
      noti()->error('Accion cancelada', 'No se puede eliminar esta Empresa de Transporte porque tiene guias asociadas');
      return redirect()->route('empresa_transporte.index');
    }


    $model->delete();
    notificacion('Accion exitosa', 'Se ha eliminado satisfactoriamente el recurso');
    return redirect()->route('empresa_transporte.index');
  }
}