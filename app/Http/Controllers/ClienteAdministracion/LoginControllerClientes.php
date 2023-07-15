<?php

namespace App\Http\Controllers\ClienteAdministracion;
use App\ClienteProveedor;
use App\Empresa;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginClienteRequest;
use Illuminate\Http\Request;

class LoginControllerClientes extends Controller
{
  public function login( LoginClienteRequest $request )
  {    
    $empcodi = is_online() ? Empresa::findByRuc($request->ruc_empresa)->empcodi : '001';
    $cliente = $cliente = ClienteProveedor::where( 'PCRucc' , $request->documento)
    ->where('EmpCodi', $empcodi )
    ->where('TipCodi', 'C')
    ->first();

    session()->flush();
    session()->put('PCCodi', $cliente->PCCodi );
    session()->put('EmpCodi_Cliente', $empcodi );

    return redirect()->route('cliente_administracion.index');
  }

  public function logout(Request $request)
  {
    session()->flush();
    return redirect()->route('busquedaDocumentos');
  }
}