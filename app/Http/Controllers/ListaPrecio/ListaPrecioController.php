<?php

namespace App\Http\Controllers\ListaPrecio;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListaPrecioRequest;
use App\Jobs\CreateUnidsForLista;
use App\ListaPrecio;
use App\Local;
use CreateUnidadTable;
use Illuminate\Http\Request;

class ListaPrecioController extends Controller
{
  public $listaprecio;

  public function __construct()
  {
    $this->middleware(p_midd('A_INDEX', 'R_LISTAPRECIO'))->only(['index']);
    $this->middleware(p_midd('A_CREATE', 'R_LISTAPRECIO'))->only(['create', 'store']);
    $this->middleware(p_midd('A_EDIT', 'R_LISTAPRECIO'))->only(['edit', 'update']);
    $this->middleware(p_midd('A_DELETE', 'R_LISTAPRECIO'))->only(['destroy']);
    $this->listaprecio = new ListaPrecio();
  }

  public function index()
  {
    return view('listaprecio.index', [
      'listaprecios' => ListaPrecio::all()
    ]);
  }

  public function create()
  {
    $empresa = get_empresa();
    $locales = $empresa->locales;
    $listas = $empresa->listas;

    return view('listaprecio.create', ['listaprecio' => $this->listaprecio, 'locales' => $locales, 'listas' => $listas]);
  }

  public function store(ListaPrecioRequest $request)
  {
    $data = $request->only('LisNomb', 'LocCodi');
    $data['empcodi'] = empcodi();
    $data['LisCodi'] = ListaPrecio::getId();
    ListaPrecio::create($data);
    $unidCreator = new CreateUnidsForLista($data['LisCodi'], $request->LocCodiCopy);
    $unidCreator->handle();

    notificacion('Registro exitoso', "Se ha registrado satisfactoriamente la nueva lista");
    return redirect()->route('listaprecio.index');
  }

  public function show($id)
  {
    return redirect()->route('listaprecio.index');
  }

  public function edit($id)
  {
    $listaprecio = ListaPrecio::find($id);
    $empresa = get_empresa();

    $locales = $empresa->locales;
    return view('listaprecio.edit', ['listaprecio' => $listaprecio, 'locales' => $locales]);
  }

  public function update(ListaPrecioRequest $request, $id)
  {
    ListaPrecio::find($id)->update([
      'LisNomb' => $request->LisNomb,
    ]);

    notificacion('Registro exitoso', "Se ha modificado satisfactoriamente la nueva lista");
    return redirect()->route('listaprecio.index');
  }

  public function destroy($id)
  {
    $empresa = get_empresa();

    $listaprecio = ListaPrecio::find($id);
    // dd($listaprecio);
    $unilista = $empresa->unidades->where('LisCodi', $id)->count();

    if ($unilista) {
      notificacion('No se puede eliminar', "Hay unidades de productos que estan usando esta lista", "error");
      return redirect()->back();
    } else {
      if ($empresa->listas->count() == 1) {
        notificacion('No se puede eliminar', "No puede quedarse sin listas", "error");
        return redirect()->back();
      }
    }

    $listaprecio->delete();

    notificacion('Borrado exitoso', "Se ha eliminado satisfactoriamente la lista");
    return redirect()->route('listaprecio.index');
  }

}
