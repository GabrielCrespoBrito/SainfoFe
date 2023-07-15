<?php

namespace App\Http\Controllers;

use App\FormaPago;
use Illuminate\Http\Request;
use App\Http\Requests\FormaPagoRequest;

class FormaPagoController extends Controller
{
  public function __construct()
  {
    $this->model = new FormaPago;
    $this->middleware(p_midd('A_INDEX', 'R_FORMAPAGO'))->only('index');
    $this->middleware(p_midd('A_EDIT', 'R_FORMAPAGO'))->only('edit');
    $this->middleware(p_midd('A_CREATE', 'R_FORMAPAGO'))->only('create');
    $this->middleware(p_midd('A_DELETE', 'R_FORMAPAGO'))->only('destroy');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('formas_pago.index', [
      'fps' => $this->model->all()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('formas_pago.create', [
      'model' => $this->model
    ]);
  }

  /**
   *  a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(FormaPagoRequest $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_FORMAPAGO'));

    $this->model->repository()->create($request->validated());
    return response()->json(['message' => 'Forma de pago registrada exitosamente']);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    return view('formas_pago.edit', [
      'model' => FormaPago::findOrfail($id)
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(FormaPagoRequest $request, $id)
  {
    $this->authorize(p_name('A_EDIT', 'R_FORMAPAGO'));

    $this->model->repository()->update($request->validated(), $id);
    return response()->json(['message' => 'Forma de pago modificada exitosamente']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $fp = FormaPago::findOrfail($id);

    if($fp->isSystem()){
      noti()->error('No permitida', 'Esta forma de pago no se puede eliminar');
      return redirect()->route('formas-pago.index');
    }


    if ($fp->ventas->count()) {
      noti()->error('No permitida', 'Esta forma de pago tiene asociada a alguna venta');
      return redirect()->route('formas-pago.index');
    }

    foreach ($fp->dias as $dia) {
      $dia->delete();
    }

    $fp->delete();
    notificacion('Acción exitosa', 'Forma de pago eliminada exitosamente');
    return redirect()->route('formas-pago.index');
  }
}
