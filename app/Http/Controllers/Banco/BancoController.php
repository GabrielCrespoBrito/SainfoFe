<?php

namespace App\Http\Controllers\Banco;

use App\Caja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banco\BancoAperturaRequest;
use App\Http\Requests\Banco\BancoCerrarRequest;
use App\Http\Requests\Banco\BancoDestroyRequest;
use App\Http\Requests\Banco\BancoReaperturaRequest;

class BancoController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_APERTURADAS', 'R_CUENTA'))->only('index');
  }

  public function search(Request $request)
  {
    $cuenta_id = $request->cuenta_id;
    $busqueda =
      Caja::query()->with('mes')
      ->where('CueCodi', $cuenta_id)
      ->where('EmpCodi', empcodi())
      ->orderBy('CajNume', 'desc');

    return datatables()
      ->of($busqueda)
      ->addColumn('column_accion', 'banco.partials.column_accion')
      ->addColumn('column_link', 'banco.partials.column_link')
      ->rawColumns(['column_accion', 'column_link'])
      ->toJson();
  }

  public function index()
  {
    $empresa = get_empresa();

    $bancos = $empresa->bancos;

    if( ! $bancos->count() ){
      noti()->warning('Necesita Crear una Cuenta Bancaria');
      return redirect()->route('cuenta.index');
    }

    $bancos = $empresa->bancos->groupBy('BanCodi');
    $usuarios = $empresa->empresa_usuarios;
    $last_id = $empresa->ultima_caja();
    return view('banco.index', compact('bancos'));
  }

  public function apertura(BancoAperturaRequest $request)
  {
    $this->authorize(p_name('A_APERTURAR', 'R_CUENTA'));
    Caja::Aperturar($request, true);
    return response()->json(['message' => 'Acción exitosa']);
  }

  public function reaperturar(BancoReaperturaRequest $request)
  {
    $this->authorize(p_name('A_REAPERTURAR', 'R_CUENTA'));
    $caja =  Caja::find($request->id_caja);
    $caja->reaperturar();
    return response()->json(['message' => 'Caja reaperturada exitosamente']);
  }


  public function cerrar(BancoCerrarRequest $request)
  {
    $this->authorize(p_name('A_CERRAR', 'R_CUENTA'));
    $caja =  Caja::find($request->id_caja);
    $caja->cerrar();
    return response()->json(['message' => 'Caja cerrada exitosamente']);
  }

  public function destroy(BancoDestroyRequest $request)
  {
    $this->authorize(p_name('A_DELETE', 'A_APERTURADAS' ,'R_CUENTA'));

    $caja =  Caja::find($request->id_caja);
    $caja->eliminar();
    return response()->json(['message' => 'Caja borrada exitosamente']);
  }
}
