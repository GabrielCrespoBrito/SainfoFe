<?php

namespace App\Http\Controllers\Suscripcion;

use App\Events\OrdenhasPay;
use Illuminate\Http\Request;
use App\Util\PDFGenerator\PDFDom;
use App\Events\OrdenPagoHasCreated;
use App\Http\Controllers\Controller;
use App\Models\Suscripcion\OrdenPago;
use App\Util\PDFGenerator\PDFGenerator;
use App\Models\Suscripcion\PlanDuracion;
use App\Http\Requests\Suscripcion\OrdenPagoStoreRequest;

class OrdenPagoController extends Controller
{
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$ordenes = OrdenPago::where('empresa_id' , empcodi())->get();
		return view('orden_pago.index', compact('ordenes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function indexAdmin()
	{
		$ordenes = OrdenPago::with('empresa','user', 'planduracion')->get();
		return view('orden_pago.index_admin', compact('ordenes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(OrdenPagoStoreRequest $request, $planduracion_id)
	{
		$planduracion = PlanDuracion::findOrfail($planduracion_id);

		$orden_pago = OrdenPago::createFromPlanDuracion($planduracion, empcodi(), auth()->user()->id() );

		event(new OrdenPagoHasCreated($orden_pago));

		return redirect()->route('suscripcion.ordenes.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$orden = OrdenPago::findOrfail($id);
		return view('orden_pago.show', compact('orden'));
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function showAdmin($id)
	{
		$orden = OrdenPago::with('empresa', 'user', 'planduracion')->findOrfail($id);

		return view('orden_pago.showAdmin', compact('orden'));
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function activar($orden_id)
	{
		// $orden_pago = OrdenPago::with('empresa', 'user', 'planduracion')->findOrfail($id);
		$orden_pago = OrdenPago::findOrfail($orden_id);

		$orden_pago->update([
			'estatus' => OrdenPago::PAGADA
		]);

		event(new OrdenhasPay($orden_pago));

		notificacion( 'Acción exitosa',  "Orden de pago #{$orden_pago->getIdFormat()} ha sido procesada exitosamente", 'success');
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
		$view = view('orden_pago.pdf', $data );
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

		event( new OrdenhasPay($orden_pago) );

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
