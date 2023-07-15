<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
  public function create($id_user)
  {
    $user = User::find($id_user);
    $roles = Role::all();
    return view('admin.usuarios.assign_role', compact('user', 'roles'));
  }

  public function store(Request $request, $id_user)
  {
    $user = User::find($id_user);
    $user->syncRoles($request->roles);
    notificacion('Roles asignados', 'Se ha agregado/remove los roles al usuario correctamente', 'success');
    return redirect()->route('admin.usuarios.index');
  }
}
