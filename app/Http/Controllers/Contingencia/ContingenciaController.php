<?php

namespace App\Http\Controllers\Contingencia;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\SummaryContigence\SummaryContigence;
use App\Http\Requests\Contingencia\ContingenciaStoreRequest;
use App\Http\Requests\Contingencia\ContingenciaUpdateRequest;
use App\Models\Contingencia\Contingencia;
use App\Models\Contingencia\ContingenciaDetalle;
use App\Models\Contingencia\ContingenciaMotivo;
use App\Venta;
use Illuminate\Http\Request;

class ContingenciaController extends Controller
{
  public function __construct()
  {
    $this->contingencia = new Contingencia();
    $this->middleware(p_midd('A_CONTINGENCIA', 'R_VENTA'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $contingencias = Contingencia::where('empcodi', empcodi())->get()->sortByDesc('docnume');
    return view('contingencia.index', ['contingencias' => $contingencias]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $motivos = ContingenciaMotivo::all();
    return view('contingencia.create', [
      'contingencia' => $this->contingencia,
      'motivos' => $motivos
    ]);
  }


  public function addItems(Request $request)
  {
    $contingenciaPendientes = Venta::getContingenciaPendiente();

    if ($contingenciaPendientes->result) {
      return response()->json(['data' => $contingenciaPendientes->data], 200);
    }

    return response()->json(['data' => 'No se encontrarón documentos de contingencia pendientes'], 400);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ContingenciaStoreRequest $request)
  {

    try {
      $contingencia = Contingencia::create($request->get('fecha'));
      $contingencia->createDetalle($request->get('items'));
    } catch (\Throwable $e) {
      $e->getMessage();
      return response()->json($e, 400);
    }
    notificacion('Acción exitosa', 'Se ha guardado exitosamente el resumen de contingencia', 'success');
    return response(['data' => 'Guardado exitoso', 'success']);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    return view('contingencia.show', [
      'contingencia' => $this->contingencia->findOrfail($id),
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $contingencia =  $this->contingencia->findOrfail($id);

    if ($contingencia->hasTicket()) {
      notificacion('Contingencia cerrada', 'Esta contingencia ya esta cerrada', 'warning');
      return redirect()->route('contingencia.show', $contingencia->id);
    }

    $motivos = ContingenciaMotivo::all();

    return view('contingencia.edit', [
      'contingencia' => $contingencia,
      'motivos' => $motivos
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(ContingenciaStoreRequest $request, $id)
  {
    $success = false;
    \DB::beginTransaction();
    try {
      $contingencia = Contingencia::with('detalles')->findOrfail($id);
      if ($request->has('ticket')) {
        $contingencia->update(['ticket' => $request->ticket]);
      }
      $contingencia->refresh();
      $contingencia->deleteDetalles();
      $contingencia->createDetalle($request->get('items'));
      \DB::commit();
      $success = true;
    } catch (\Throwable $e) {
      $e->getMessage();
      \DB::rollback();
      return response()->json($e, 400);
    }
    notificacion('Acción exitosa', "Se ha actualizado exitosamente la contingencia {$contingencia->docnume}", 'success');
    return response(['data' => 'Guardado exitoso', 'success']);
  }

  public function generateTxt($id)
  {
    $contingencia = Contingencia::find($id);
    $ids = $contingencia->detalles->pluck('vtaoper')->toArray();
    $summary = new SummaryContigence($ids, $contingencia->docnume);
    $path = $summary->generate();
    return response()->download($path);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $contingencia = $this->contingencia->findOrfail($id);
    $name = $contingencia->docnume;

    if ($contingencia->hasTicket()) {
      notificacion('Acción invalida', "No se puede eliminar un resumen de contingencia con ticket", 'warning');
      return redirect()->route('contingencia.index');
    }

    notificacion('Acción exitosa', "Se ha eliminado el resumen de contingencia ({$name}) exitosamente", 'success');

    $contingencia->deleteDetalles();
    $contingencia->delete();

    notificacion('Acción exitosa', "Se ha eliminado el resumen de contingencia ({$name}) exitosamente", 'success');
    return redirect()->route('contingencia.index');
  }
}
