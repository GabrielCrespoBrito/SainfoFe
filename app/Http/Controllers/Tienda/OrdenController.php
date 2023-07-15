<?php

namespace App\Http\Controllers\Tienda;

use App\Cotizacion;
use App\Models\Tienda\Orden;
use Illuminate\Http\Request;
use App\Models\Tienda\OrdenStat;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Tienda\MetaData;

class OrdenController extends Controller
{
	public function search(Request $request)
	{
		// 
		$query = Orden::with(['info' => function ($query) {
			return $query->whereIn('meta_key', ['ywraq_other_email_content', '_billing_phone', '_billing_first_name',
				MetaData::COTIZACION_ID
			]);
		}, 'stat.cliente'])->orderBy('ID', 'desc');

		// Filtrar por status
		if ($request->input('status') != 'todos') {
			$query->whereHas('stat', function ($q) use ($request) {
				$q->where('status',  $request->input('status'));
			});
		}

		return DataTables::of($query)
			->addColumn('link', function ($model) {
				return  sprintf("<a href='%s'>%s</a>", route('tienda.orden.show', $model->ID), $model->ID);
			})
			->addColumn('status', 'tienda.orden.partials.column_status')
			->addColumn('documento', 'tienda.orden.partials.column_doc')
			->addColumn('tlf', 'tienda.orden.partials.column_telf')
			->addColumn('info_form', 'tienda.orden.partials.column_info_form')
			->addColumn('accion', 'partials.column_accion_model')
			->rawColumns(['accion', 'link', 'ID', 'info_form', 'tlf', 'documento', 'status'])
			->make(true);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$status = OrdenStat::getStatus();
		$status_nuevo = OrdenStat::STATUS_NUEVO;
		// dd($status_nuevo);
		return view('tienda.orden.index', ['status' => $status, 'status_nuevo' => $status_nuevo]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$orden = Orden::with(['stat.cliente.user', 'items.producto.info', 'info'])->findOrfail($id);
		$data = $orden->getFormatData();
		return view('tienda.orden.show', ['data' => $data]);
	}

	/**
	 * Generar cotizacion
	 * 
	 * @param int $id
	 * @return Response
	 */

	public function generarCotizacion($id)
	{
		return redirect()->route('coti.create', ['tipo' => Cotizacion::COTIZACION , 'orden_id' => $id  ]);
	}
}
