<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserPermissionController extends Controller
{
  public function edit($user_id)
  {
    $user = User::findOrfail($user_id);
    $permisos_group = Permission::where('is_admin', 0)->get()->groupBy('group');
    return view('admin.usuarios-permisos.edit', compact('user', 'permisos_group'));
  }

  public function update(Request $request, $user_id)
  {        
    $user = User::findOrfail($user_id);
    $user->syncPermissions($request->permisos);
    noti()->success('Permisos asignados', "Se ha establecido exitosamente los permisos para el usuario {$user->nombre()}");
    return redirect()->back();
  }  
}
