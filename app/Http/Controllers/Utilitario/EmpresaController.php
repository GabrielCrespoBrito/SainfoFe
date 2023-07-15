<?php

namespace App\Http\Controllers\Utilitario;

use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\ModuloMonitoreo\Empresa\Empresa;
use App\Events\Monitoreo\Empresa\EmpresaHasUpdated;
use App\Http\Requests\Monitoreo\EmpresaCreateRequest;
use App\Events\Monitoreo\Empresa\EmpresaMonitoreoHasCreated;
use App\ModuloMonitoreo\DocSerie\DocSerie;

class EmpresaController extends Controller
{
	public $empresa;

	public function __construct()
	{
		$this->empresa = new Empresa;
		$this->serie = new DocSerie();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function search( Request $request )
	{
		$busqueda = $this->empresa->query();
		
		return DataTables::of($busqueda)
		->addColumn('code', function($model){
			return sprintf("<a href='%s'>%s</a>", route('monitoreo.empresas.show', $model->id), $model->code);
		})
			->addColumn('cant_docs', function ($model) {
				return sprintf("<a href='%s' class='btn btn-xs btn-default'>%s
				</a>", route('monitoreo.empresas.docs', $model->id) , $model->cant_docs);
			})
		->addColumn('active', function ($model) {
			
				return $model->active ?
				"<a href='#' class='btn btn-xs btn-default'>
					<span class='fa fa-check-square-o'></span>
				</a>" :
				
				"<a href='#' class='btn btn-xs btn-default'>
					<span class='fa fa-square-o'></span>				
				</a>";
			})
			->addColumn('column_accion', 'partials.column_model' )
		->rawColumns(['code','active', 'column_accion', 'cant_docs'])
		->make(true);  		
	}

	public function index()
	{
		return view('modulo_monitoreo.empresas.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('modulo_monitoreo.empresas.create', [
			'empr' => $this->empresa, 
			'tipos_documentos' => TipoDocumentoPago::getValidTipoSunat(),
			'serie_fake' => $this->serie,
			] );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(EmpresaCreateRequest $request)
	{
		$empresa = Empresa::create($request->all());
		event( new EmpresaMonitoreoHasCreated($empresa, $request));
		$redirectTo = $request->has('createNew') ? route('monitoreo.empresas.create') : route('monitoreo.empresas.index');
		return response()->json(['data' => 'Empresa guardada exitosamente' , 'redirect' => $redirectTo  ]);		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return view('modulo_monitoreo.empresas.edit', [
			'empr' => $this->empresa->find($id),
			'tipos_documentos' => TipoDocumentoPago::getValidTipoSunat(),
			'serie_fake' => $this->serie,
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
		return view('modulo_monitoreo.empresas.create');
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(EmpresaCreateRequest $request, $id)
	{
		$empresa = $this->empresa->find($id);
		$empresa->update($request->all());
		event(new EmpresaHasUpdated($empresa, $request));
		$redirectTo = $request->has('createNew') ? route('monitoreo.empresas.create') : route('monitoreo.empresas.index');
		return response()->json(['data' => 'Empresa modificada exitosamente', 'redirect' => $redirectTo]);	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		function rrmdir($src)
		{
			$dir = opendir($src);
			while (false !== ($file = readdir($dir))) {
				if (($file != '.') && ($file != '..')) {
					$full = $src . '/' . $file;
					if (is_dir($full)) {
						rrmdir($full);
					} else {
						unlink($full);
					}
				}
			}
			closedir($dir);
			rmdir($src);
		}


		$empresa = Empresa::find($id);
		$path = $empresa->getFolderSave();
		if (is_dir($path)) {
			rrmdir($path);
		} 
		$empresa->delete();

		notificacion('Acción exitosa', 'Empresa eliminada exitosamnete');	
		return back();
	}

}
