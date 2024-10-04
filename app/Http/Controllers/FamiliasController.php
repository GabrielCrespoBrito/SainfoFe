<?php

namespace App\Http\Controllers;

use App\Familia;
use App\Grupo;
use App\Http\Requests\FamiliaDeleteRequest;
use App\Http\Requests\FamiliaStoreRequest;
use App\Http\Requests\FamiliaUpdateRequest;
use App\Producto;
use Illuminate\Http\Request;

class FamiliasController extends Controller
{
  public function __construct()
  {
    $this->middleware([p_midd('A_INDEX', 'R_FAMILIA'),'familia.producto.creacion'])->only(['index', 'search']);
  }

	public function index( $create = 0 )
  {	
    $grupos = Grupo::where('empcodi', empcodi())->get();
		return view('familias.index', compact('grupos','create') );
	}

	public function search( Request $request )
	{

    $deleted = $request->input('deleted', 0);
    $condition = $deleted ? '=' : '!=';
    $search = Familia::query()
    ->where('UDelete', $condition, "*")
    ->with(['grupo' => function($query){
      $query->where('empcodi', empcodi());
    }]);

		return datatables()->of($search)
    ->addColumn('acciones', 'familias.partials.column_acciones')
    ->rawColumns(['acciones'])
    ->toJson();
	}

  public function restaurar($id, $id_grupo = null)
  {
    $familia = Familia::findComplete($id, $id_grupo);
    $familia->deleteRevert();
    noti()->success('Acción exitosa', 'Se ha restaurado la familia');
    return back();
  }



	public function buscar_grupo(Request $request)
	{
		$grupo = Grupo::find( $request->id_grupo )->where('empcodi',empcodi())->get();
		return $grupo->familias;
	}

	public function guardar( FamiliaStoreRequest $request )
	{
    $this->authorize(p_name('A_CREATE', 'R_FAMILIA'));

    $data = $request->all();
    $data['empcodi'] = empcodi();
    $familia = Familia::create($data); 
    return response()->json(['data' => 'Familia guardada exitosamente' , 'last_id' => Familia::last_id($familia->gruCodi) ]);
	}


	public function editar( FamiliaUpdateRequest $request)
	{
    $this->authorize(p_name('A_EDIT', 'R_FAMILIA'));

    $familia = 
    Familia::where('famCodi' , $request->famCodi)
    ->where('gruCodi', $request->gruCodi)
    ->where('empcodi', empcodi() )    
    ->first();
    
		$familia->famNomb = $request->famNomb;
		$familia->save();
    return response()->json(['data' => 'Familia guardada exitosamente' , 'last_id' => Familia::last_id($familia->gruCodi) ]);    
	}

  public function borrar( FamiliaDeleteRequest $request )
  {
    $this->authorize(p_name('A_DELETE', 'R_FAMILIA'));

    Familia::where('famCodi' , $request->id)
    ->where('gruCodi', $request->id_grupo)
    ->where('empcodi', empcodi())   
    ->first()
    ->deleteSoft();



    

    
    return response()->json(['data' => 'Familia eliminada exitosamente' , 'last_id' => Familia::last_id($request->id_grupo) ]);
  }



}
