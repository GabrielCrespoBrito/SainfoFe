<?php

namespace App\Http\Controllers\Admin;

use App\Empresa;
use App\Events\OrdenhasPay;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Util\PDFGenerator\PDFDom;
use App\Events\OrdenPagoHasCreated;
use App\Http\Controllers\Controller;
use App\Models\Suscripcion\OrdenPago;
use App\Util\PDFGenerator\PDFGenerator;
use App\Models\Suscripcion\PlanDuracion;
use App\Notifications\OrdenPagoHasProcess;
use App\Http\Requests\Suscripcion\OrdenPagoStoreRequest;

class OrdenPagoController extends Controller
{
  public function index()
  {
    return view('admin.orden_pago.index');
  }

  public function search( Request $request )
  {
    $busqueda = OrdenPago::with('empresa', 'user', 'planduracion');

    if( $estatus =  $request->input('estatus')){
      $busqueda = $busqueda->where('estatus' , $estatus);
    }

    $dataTable = DataTables::of($busqueda)
      ->addColumn('link', 'admin.orden_pago.partials.column_link')
      ->addColumn('accion', 'admin.orden_pago.partials.column_accion')
      ->addColumn('plan', 'admin.orden_pago.partials.column_plan')
      ->addColumn('usuario', 'admin.orden_pago.partials.column_usuario')
      ->addColumn('empresa', 'admin.orden_pago.partials.column_empresa')
      ->addColumn('estado', 'admin.orden_pago.partials.column_estado')
      ->rawColumns([ 'link', 'accion',  'plan', 'usuario','empresa', 'estado'])
      ->make(true);

    return $dataTable;    
  }

  public function store(OrdenPagoStoreRequest $request, $planduracion_id)
  {
    $planduracion = PlanDuracion::findOrfail($planduracion_id);

    $orden_pago = OrdenPago::createFromPlanDuracion($planduracion, empcodi(), auth()->user()->id());

    event(new OrdenPagoHasCreated($orden_pago));

    return redirect()->route('suscripcion.ordenes.index');
  }

  public function storeEscritorio(Request $request, $empresa_id)
  {
    $empresa = Empresa::find($empresa_id);
    $planduracion = PlanDuracion::findOrfail($request->plan);
    $user = $empresa->userOwner();
    $isPagada = $request->estatus == "pagada"; 

    $orden_pago = OrdenPago::createFromPlanDuracion($planduracion, empcodi(), $user->id(),  $isPagada, $request->all() );

    event(new OrdenPagoHasCreated($orden_pago));

    if($isPagada){
      $orden_pago->createSuscripcion( $request->input('fecha_final'));
      $user->notify(new OrdenPagoHasProcess($orden_pago, $empresa));
    }
    else {
      $orden_pago->fecha_vencimiento = $request->input('fecha_final');
      $orden_pago->save();
    }

    noti()->success('Orden Creada Satisfactoriamente');
    return back();
  }

  public function show($id)
  {
    $orden = OrdenPago::with('empresa', 'user', 'planduracion')->findOrfail($id);
    return view('admin.orden_pago.show', compact('orden'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function activar($orden_id)
  {
    $orden_pago = OrdenPago::findOrfail($orden_id);

    $orden_pago->update([
      'estatus' => OrdenPago::PAGADA
    ]);

    event(new OrdenhasPay($orden_pago));

    noti()->success('Acción exitosa',  "Orden de pago #{$orden_pago->getIdFormat()} ha sido procesada exitosamente");
    return redirect()->back();
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function pdf($id)
  {
    $orden_pago = OrdenPago::findOrfail($id);
    $data = $orden_pago->dataPDF();
    $view = view('admin.orden_pago.pdf', $data);
    $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
    $generator->generator->setGlobalOptions([
      'orientation' => 'portrait'
    ]);
    return $generator->generate();
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function changeEstatus($orden_id)
  {
    $orden_pago = OrdenPago::findOrfail($orden_id);

    $orden_pago->update([
      'estatus' => OrdenPago::PAGADA
    ]);

    event(new OrdenhasPay($orden_pago));

    return redirect()->back();
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
