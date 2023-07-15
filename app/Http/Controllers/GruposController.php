<?php

namespace App\Http\Controllers;

use App\Familia;
use App\Grupo;
use App\Http\Requests\GrupoDeleteRequest;
use App\Http\Requests\GrupoStoreRequest;
use App\Http\Requests\GrupoUpdateRequest;
use Illuminate\Http\Request;

class GruposController extends Controller
{
	public function __construct()
	{
		$this->middleware(p_midd('A_INDEX','R_GRUPO'))->only(['index', 'search']);
	}

	public function index($create = 0)
	{
    $last_id = Grupo::last_id();                
		return view('grupos.index', compact('create','last_id') );
	}

	public function search()
	{
		return datatables()->of(Grupo::query()->where('empcodi', empcodi() ))->toJson();
	}


  public function searchApi()
  {
    return Grupo::with('fams')->get();
  }



	public function buscar_grupo(Request $request)
	{
		$grupo = Grupo::find( $request->id_grupo );
		return $grupo->familias();
	}

 
	public function guardar(GrupoStoreRequest $request )
	{
    $this->authorize(p_name('A_CREATE','R_GRUPO'));

		$data = $request->all();
		$data['GruEsta'] = 1;
		$data['empcodi'] = empcodi();
		$grupo = Grupo::create($data);    
    $grupo->createFamiliaDefault();
		return response()->json(['data' => 'Grupo creado exitosamente' , 'last_id' => Grupo::last_id()]);
	}


	public function editar(GrupoUpdateRequest $request)
	{
    $this->authorize(p_name('A_EDIT', 'R_GRUPO'));

		$grupo = Grupo::find($request->GruCodi);
		$grupo->GruNomb = $request->GruNomb;
		$grupo->save();
		return response()->json(['data' => 'Grupo modificado exitosamente' , 'last_id' => Grupo::last_id()]);
	}

  public function eliminar( GrupoDeleteRequest $request )
  {
    $this->authorize(p_name('A_DELETE', 'R_GRUPO'));

		Grupo::where('gruCodi', $request->id)
		->where('empcodi', empcodi())
		->first()
		->delete();
		return response()->json(['data' => 'Grupo eliminado exitosamente' , 'last_id' => Grupo::last_id()]);		

  }

}
