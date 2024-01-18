<?php

namespace App\Http\Controllers;

use App\Zona;
use Illuminate\Http\Request;
use App\Http\Requests\ZonaRequest;
use App\Http\Requests\ZonaDestroyRequest;

class ZonaController extends Controller
{
  public $model;

  public function __construct()
  {
    $this->model = new Zona();

    $this->middleware(p_midd('A_INDEX', 'R_ZONA'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_ZONA'))->only('create', 'store');
    $this->middleware(p_midd('A_EDIT', 'R_ZONA'))->only('edit', 'update');
    $this->middleware(p_midd('A_DELETE', 'R_ZONA'))->only('destroy');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $zonas = $this->model->get();
    return view('zonas.index', compact('zonas'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('zonas.create', ['model' => $this->model, 'create' => true ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ZonaRequest $request)
  {
    $this->model->create($request->only('ZonCodi', 'ZonNomb'));

    notificacion('Acción exitosa', 'La Zona se ha creado exitosamente');
    return redirect()->route('zonas.index');
  }



  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $create = false;
    $model = Zona::findOrfail($id);
    return view('zonas.edit', compact('create', 'model'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(ZonaRequest $request, $id)
  {
    $this->model->find($id)->update($request->only('ZonNomb'));
    notificacion('Acción exitosa', 'La Zona se ha modificada exitosamente');
    return redirect()->route('zonas.index');
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(ZonaDestroyRequest $request, $id)
  {
    $this->model->find($id)->delete($id);
    notificacion('Acción exitosa', 'El vendedor ha sido eliminado exitosamente');
    return redirect()->route('zonas.index');
  }
}
