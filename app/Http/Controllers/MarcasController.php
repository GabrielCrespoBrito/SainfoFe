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
    return view('marcas.index', compact('create', 'last_id'));
  }

  public function search(Request $request)
  {
    $this->authorize(p_name('A_INDEX', 'R_MARCA'));

    $deleted = $request->input('deleted', 0);
    $condition = $deleted ? '=' : '!=';

    return datatables()
      ->of(Marca::query()
        ->where('UDelete', $condition, "*"))
      ->addColumn('acciones', 'marcas.partials.column_acciones')
      ->rawColumns(['acciones'])

      ->toJson();
  }


  public function restaurar($id)
  {
    $marca = Marca::find($id);
    $marca->deleteRevert();
    noti()->success('Acción exitosa', 'Se ha restaurado la marca');
    return back();
  }


  public function buscar_grupo(Request $request)
  {
    $grupo = Grupo::find($request->id_grupo);
    return $grupo->familias;
  }

  public function guardar(MarcaStoreRequest $request)
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

    if ($marca->isDirty()) {
      $marca->save();
    }

    return response()->json([
      'data' => 'Se ha modificado exitosamente la marca',
      'last_id' => Marca::last_id()
    ]);
  }

  public function eliminar(MarcaDeleteRequest $request)
  {
    $this->authorize(p_name('A_DELETE', 'R_MARCA'));

    $marca = Marca::find($request->MarCodi);
    $nombre = $marca->MarNomb;
    $marca->deleteSoft();

    return response()->json([
      'data' => "Se ha borrado exitosamente la marca ($nombre)",
      'last_id' => Marca::last_id()
    ]);
  }

  /*
  public function eliminar(Request $request)
  {
    $this->authorize(p_name('A_DELETE', 'R_PRODUCTO'));

    $producto = Producto::findByProCodi($request->id);
    $result = $producto->useInDocument();

    if (! $result->success) {
      if ($result->codigo_sitio == "toma_inventario") {
        return response()->json(['message' => sprintf("No puede cambiar eliminar el producto por que esta siendo utiliza en Toma de Inventario")], 400);
      }
    }


    if (!$result->success) {
      $producto->UDelete = "*";
      $producto->save();
      return response()->json(['message' => 'Producto Ocultado'], 200);
    } else {
      foreach ($producto->unidades as $unidad) {
        Unidad::destroy($unidad->Unicodi);
        $unidad->delete();
      }
      $producto->delete();
      return response()->json(['message' => 'Producto eliminado'], 200);
    }
  }

  */
}
