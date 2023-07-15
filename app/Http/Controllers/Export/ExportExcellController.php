<?php

namespace App\Http\Controllers\Export;

use App\Mes;
use App\Venta;
use App\Compra;
use App\Http\Controllers\Controller;
use App\Util\ExcellGenerator\CompraVentaExport;
use App\Http\Requests\ExportExcell\ExportExcellRequest;

class ExportExcellController extends Controller
{
	public function __construct()
	{
    $this->middleware(p_midd('A_EXPORTAR_COMPRAVENTA', 'R_UTILITARIO'))->only('show');
	}

	/**
	 * Formulario para mostrar el formulario para exportar excell
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		$para = ['sainfo' => 'Sainfo', 'concar' => 'Concar'];
		$tipo = ['compras' => 'Compras', 'ventas' => 'Ventas'];
		$meses = Mes::all()->reverse()->pluck('mesnomb', 'mescodi');
		return view('export.show', compact('para','tipo', 'meses'));
	}

	/**
	 * Formulario para mostrar el formulario para exportar excell
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generate( ExportExcellRequest $request )
	{
    $this->authorize(p_name('A_EXPORTAR_COMPRAVENTA', 'R_UTILITARIO'));

    ob_end_clean();
		$data = $request->tipo == "ventas" ? 
		Venta::getDataExcell( $request->periodo, $request->para ) : 
		Compra::getDataExcell($request->periodo, $request->para);  
    
    $nameFile = 
    strtoupper( $request->tipo ) .
    get_empresa()->ruc() .
    $request->periodo;
    
		$excellExport = new CompraVentaExport($data , $request->para, $request->tipo, $nameFile );
    
    $info = $excellExport
    ->generate()
   ->store();

    return response()->download($info['full'], $info['file']);
	}
}