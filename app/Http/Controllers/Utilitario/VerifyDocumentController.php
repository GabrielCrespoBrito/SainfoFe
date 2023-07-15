<?php

namespace App\Http\Controllers\Utilitario;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\ModuloMonitoreo\Document\Document;
use App\ModuloMonitoreo\Empresa\Empresa;

class VerifyDocumentController extends Controller
{
	public function __construct()
	{ }

	public function search(Request $request)
	{
		$busqueda = Document::empresa($request->empresa_id)
			->serie($request->serie);


		return DataTables::of($busqueda)
			->make(true);
	}

	public function index()
	{
		$empresas_mod = Empresa::all();
		return view('modulo_monitoreo.documents.index', ['empresas_mod' => $empresas_mod]);
	}
}
