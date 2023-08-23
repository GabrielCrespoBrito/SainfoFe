<?php

namespace App\Http\Controllers;

use App\Local;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LocalRequest;
use App\SerieDocumento;
use Illuminate\Support\Facades\Log;

class LocalController extends Controller
{
  public $model;

  public function __construct()
  {
    $this->model = new Local();
    $this->middleware(p_midd('A_INDEX', 'R_LOCAL'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_LOCAL'))->only('create');
    $this->middleware(p_midd('A_EDIT', 'R_LOCAL'))->only('edit');
    $this->middleware(p_midd('A_DELETE', 'R_LOCAL'))->only('destroy');
  }
  
  public $fieldsUpdatedAbles = [
    'LocNomb',
    'LocDire',
    'LocDist',
    'LocTele',
    'PDFLocalNombreInd',
  ];

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('locales.index', [
      'locales' => $this->model->all()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $empresa = get_empresa();
    $usuarios = $empresa->users;
    $info = $empresa->getNuevaSeriesInfo();
    $listas = $empresa->listas;

    return view('locales.create', [
      'model' => $this->model,
      'usuarios' => $usuarios,
      'series' => $info->series,
      'serie' => $info->serie,
      'listas' => $listas,
    ]);
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function searchApi()
  {
    return Local::all();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(LocalRequest $request)
  {
    ini_set('memory_limit', '4000M');
    ini_set('max_execution_time', '600');

    $this->authorize(p_name('A_CREATE', 'R_LOCAL'));
    $fields = $request->only($this->fieldsUpdatedAbles);
    $fields['SerLetra'] = $request->serie;
    $fields['PDFLocalNombreInd'] = $request->input('PDFLocalNombreInd', 0);
    $local = null;

    DB::connection('tenant')->beginTransaction();
    try {
      $local = Local::create($fields);
      $local->asociateInfo($request);
      DB::connection('tenant')->commit();
    } catch (Exception $e) {
      optional($local)->deleteAll();
      DB::connection('tenant')->rollback();
      return response()->json(['message' => 'Error al guardar el local: ' . $e->getMessage()], 500);
    }

    return response()->json(['message' => 'Se ha registrado un nuevo local exitosamente']);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $empresa = get_empresa();
    $usuarios = $empresa->users->load('locales');
    // dd($usuarios);
    // exit();
    $local = Local::with('ubigeo')->findOrfail($id);
    $series = $local->getSeries();
    $listas = $local->listas;


    return view('locales.edit', [
      'model' => $local,
      'usuarios' => $usuarios,
      'serie' => $local->SerLetra,
      'series' => $series,
      'listas' => $listas,
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(LocalRequest $request, $id)
  {
    $this->authorize(p_name('A_EDIT', 'R_LOCAL'));

    $data = $request->only($this->fieldsUpdatedAbles);
    $data['PDFLocalNombreInd'] = $request->input('PDFLocalNombreInd', 0);

    $local = Local::findOrfail($id)->fill($data);

    if ($local->isDirty($this->fieldsUpdatedAbles)) {
      $local->save();
    }
    
    $local->updateAsociateInfo($request);
    return response()->json(['message' => 'Se ha modificado exitosamente el local']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $local = Local::findOrFail($id);
    $empresa = get_empresa();

    // Si este es el unico local
    if ($empresa->locales->count() == 1) {
      notificacion('Acciòn no permitida', 'La empresa tiene que tener al menos un (1) local', 'error');
      return redirect()->route('locales.index');
    }

    if ($local->ventas->count()) {
      notificacion('Acciòn no permitida', 'El local tiene alguna venta registrada', 'error');
      return redirect()->route('locales.index');
    }

    if ($local->resumenes->count()) {
      notificacion('Acciòn no permitida', 'El local tiene alguna lista de precio registrada', 'error');
      return redirect()->route('locales.index');
    }

    if ($local->cajas->count()) {
      notificacion('Acciòn no permitida', 'El local tiene registrada alguna caja', 'error');
      return redirect()->route('locales.index');
    }

    if ($local->compras->count()) {
      notificacion('Acciòn no permitida', 'El local tiene registrada alguna compra', 'error');
      return redirect()->route('locales.index');
    }

    if ($local->cotizaciones->count()) {
      notificacion('Acciòn no permitida', 'El local tiene registrada alguna cotizaciòn', 'error');
      return redirect()->route('locales.index');
    }

    if ($local->listas->count()) {
      notificacion('Acciòn no permitida', 'El local tiene alguna lista de precio registrada', 'error');
      return redirect()->route('locales.index');
    }

    $series = $local->series->where('empcodi', $local->EmpCodi);

    foreach ($series as $serie) {
      $serie->delete();
    }

    $local->deleteAll();

    notificacion('Acciòn exitosa', 'Se ha eliminado exitosamente el local');
    return redirect()->route('locales.index');
  }
}