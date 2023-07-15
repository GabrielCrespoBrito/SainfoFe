<?php

namespace App\Util\DBHelper;

use App\User;
use App\Permission;
use PermissionSeeder;

trait PermissionsHelp
{
  public function permission_help()
  {
    $permissionSeeder = new PermissionSeeder();
    $permissions = $permissionSeeder->getPermissions();

    foreach( $permissions as $permission_data ){
      $permission = Permission::firstOrCreate(['name' => $permission_data['name'] ]);
      $permission->update($permission_data);
    }

    $this->addMessage("Se ha actualizado satisfactoriamente la información de los permisos");
  }

  /**
   * Eliminación de las tablas para el sistema de suscripciónes
   *
   * @return void
   */
  public function set_permission_to_users()
  {
    $users = User::all();
    $permissionSeeder = new PermissionSeeder();
    $permissions = collect($permissionSeeder->getPermissions());
    $permissions = $permissions->where('is_admin' , false)->pluck('name');
    foreach( $users as $user ){
        $user->giveAllPermission($permissions);
    }
  }
}
