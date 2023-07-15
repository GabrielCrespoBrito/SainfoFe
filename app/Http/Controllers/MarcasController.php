<?php

namespace App\Http\Controllers;

use App\Grupo;
use App\Marca;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;
use App\Http\Requests\MarcaStoreRequest;
use App\Http\Requests\MarcaDeleteRequest;
use App\Http\Requests\MarcaUpdateRequest;

class MarcasController extends Controller
{
	public function __construct()
	{
		$this->middleware(p_midd('A_INDEX',  'R_MARCA'))->only(['index']);
	}

  public function searchApi()
  {
    return Marca::all();
  }

	public function index($create = 0)
	{
		$last_id = Marca::last_id();		
		return view('marcas.index' , compact('create','last_id'));
	}

	public function search()
	{
    $this->authorize(p_name('A_INDEX', 'R_MARCA'));
    
    return datatables()
		->of(Marca::query()->where('empcodi', empcodi() ))
    ->addColumn('acciones', 'marcas.partials.column_acciones')
		->toJson();
	}

	public function buscar_grupo( Request $request )
	{
		$grupo = Grupo::find( $request->id_grupo );
		return $grupo->familias;
	}
 
	public function guardar(MarcaStoreRequest $request )
	{
    // loremp-ipsum-odlor
    $this->authorize(p_name('A_CREATE', 'R_MARCA'));
    
		$data = $request->all();
		$data['empcodi'] = get_empresa('id');
		Marca::create($data);

		return response()->json([
			'data' => 'Se ha guardado exitosamente la marca', 
			'last_id' => Marca::last_id()
		]);
	}

	public function editar(MarcaUpdateRequest $request)
	{
    $this->authorize(p_name('A_EDIT', 'R_MARCA'));

		$marca = Marca::findOrfail($request->MarCodi);
		$marca->fill($request->only('MarNomb'));

		if( $marca->isDirty() ){
			$marca->save();
		}

		return response()->json([
			'data' => 'Se ha modificado exitosamente la marca',
			'last_id' => Marca::last_id()
		]);
	}

  public function eliminar( MarcaDeleteRequest $request )
  {
    $this->authorize(p_name('A_DELETE', 'R_MARCA'));

		$marca = Marca::find($request->MarCodi);
		$nombre = $marca->MarNomb;
		$marca->delete();   
		return response()->json([
			'data' => "Se ha borrado exitosamente la marca ($nombre)" ,
			'last_id' => Marca::last_id()		
		]);

  }

}