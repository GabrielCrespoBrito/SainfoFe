<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Empresa;
use App\UserEmpresa;
use App\SerieDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\Http\Requests\UserEmpresaCreateRequest;
use App\Http\Requests\UserEmpresaDeleteRequest;
use App\Models\UserLocal\UserLocal;

class UserEmpresaController extends Controller
{
  public function create( $id_user = 'all', $id_empresa = 'all' )
  {
    $user = $id_user == "all" ? User::all() : User::findOrfail($id_user);
    $emps = $id_empresa == "all" ? Empresa::all() : Empresa::find($id_empresa);
    return view('admin.usuarios-empresa.create', compact('user', 'emps', 'id_user', 'id_empresa'));
  }

  public function store(UserEmpresaCreateRequest $request)
  {
    empresa_bd_tenant($request->id_empresa);
    $user_empresa = new UserEmpresa;
    $user_empresa->usucodi = $request->id_user;
    $user_empresa->empcodi = $request->id_empresa;
    $user_empresa->save();
    $user_empresa->assignToDefaultLocal();
    $user_empresa->createDefaultCaja();
    Cache::flush();    
    noti()->success('Usuario agregado', 'Se ha agregado el usuario a la empresa');
    return redirect()->route('admin.usuarios.index');
  }

  public function delete(UserEmpresaDeleteRequest $request, $id)
  {
    $user_empresa = UserEmpresa::findOrfail($id);
    $user_empresa->delete();
    noti()->success('Borrado exitoso', 'Se ha Eliminado exitosamente la información asociada de este usuario a la empresa');
    return redirect()->route('admin.usuarios.index');
  }

  public function show($id)
  {
    $user_empresa = UserEmpresa::with(['user', 'empresa'])->find($id);
    $user = $user_empresa->user;
    $empresa = $user_empresa->empresa;
    return view('admin.usuarios-empresa.show', [
      'user'    => $user,
      'empresa' => $empresa,
      'id'      => $id,
    ]);
  }

  public function searchUsers( Request $request )
  {
    $empresa = Empresa::find($request->empresa_id);
    if( $local = $request->input('local_id') ){
      $users_local = $empresa->user_local->where('loccodi' , $local );
      $users = $users_local->map(function ($userLocal) {
        return $userLocal->user;
      });
    }
    else {
      $users = $empresa->users;
    }
    $data = [];
    foreach( $users as $user ){
      $selected = $user->usucodi == session()->get('usucodi');
      $serieData = [
        'id' => $user->usucodi,
        'text' => $user->usucodi . ' - ' . $user->usulogi,
        'selected' => $selected
      ];      
      array_push( $data , $serieData );
    }
    return response()->json(['data' => $data ]);
  }  
}
