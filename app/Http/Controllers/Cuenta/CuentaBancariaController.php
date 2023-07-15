<?php

namespace App\Http\Controllers\Cuenta;

use App\Banco;
use App\Moneda;
use App\BancoEmpresa;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cuenta\CuentaDestroy;
use App\Http\Requests\Cuenta\CuentaRequest;
use App\Repositories\BancoCuentaRepository;

class CuentaBancariaController extends Controller
{
  public function __construct()
  {
    $this->model = new BancoEmpresa();
    $this->middleware(p_midd('A_INDEX', 'R_CUENTA'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_CUENTA'))->only('create','store');
    $this->middleware(p_midd('A_EDIT', 'R_CUENTA'))->only('edit','update');
    $this->middleware(p_midd('A_DELETE', 'R_CUENTA'))->only('destroy');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $cuentas = $this->model->get()->load('banco', 'moneda');
    return view('cuenta.index', compact('cuentas'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $bancos = Banco::all();
    $monedas = Moneda::all();
    $create = true;
    $bancocuenta_repository = new BancoCuentaRepository(new BancoEmpresa, empcodi());

    $model = $this->model;
    return view('cuenta.create', compact('create', 'bancos', 'monedas', 'model'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CuentaRequest $request)
  {
    $this->model->repository()->create($request->all());
    $bancocuenta_repository = new BancoCuentaRepository(new BancoEmpresa, empcodi());
    $bancocuenta_repository->clearCache('all');
    notificacion('Acción exitosa', 'La cuenta se ha creado exitosamente');
    return redirect()->route('cuenta.index');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $bancos = Banco::all();
    $monedas = Moneda::all();
    $create = false;
    $model = BancoEmpresa::findOrfail($id);
    return view('cuenta.edit', compact('create', 'bancos', 'monedas', 'model'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(CuentaRequest $request, $id)
  {
    $this->model->repository()->update($request->all(), $id);
    notificacion('Acción exitosa', 'La cuenta se ha creado modificada exitosamente');
    $bancocuenta_repository = new BancoCuentaRepository(new BancoEmpresa, empcodi());
    $bancocuenta_repository->clearCache('all');
    return redirect()->route('cuenta.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(CuentaDestroy $request, $id)
  {
    $this->model->repository()->delete($id);
    $this->model->repository()->clearCache('all');
    // $this->model->repository()->delete($id);
    // $bancocuenta_repository->clearCache('all');
    notificacion('Acción exitosa', 'La cuenta ha sido eliminada exitosamente');
    return redirect()->route('cuenta.index');
  }
}