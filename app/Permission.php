<?php

namespace App;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use PermissionSeeder;
use Spatie\Permission\Models\Permission as ModelsPermission;


class Permission extends ModelsPermission
{
  use UsesSystemConnection;

  protected $fillable = [
    'valid',
    'guard_name',
    'name',
    'group',
    'descripcion',
    'is_admin',
  ];

  const ADMIN_PERMISSIONS = [
    'actividad clientes',
    'show parametros',
    'store parametros',
    'verificar-documentos',
    'administracion',
    'contador',
    'empresa edit',
  ];

  public function nameRead()
  {
    $nameArr = explode(" ", $this->name);
    $nameArrTraslate = [];

    foreach ($nameArr as $namePart) {
      array_push( $nameArrTraslate,  __('messages.' . $namePart));
    }

    return implode(' ', $nameArrTraslate);
  }

  /**
   * Nombre para poder del usuario
   * 
   * @return string
   */
  public function isAdmin()
  {
    return in_array(self::ADMIN_PERMISSIONS, $this->name);
  }

  /**
   * Poner como no validos todos registros.
   * 
   */
  public static function invalidAll()
  {
    // guardar
    return Permission::query()->update(['valid' => 0]);
  }

  /**
   * Poner como no validos todos registros.
   * 
   */
  public static function deleteInvalid()
  {
    $permissions = Permission::where('valid', 0)->get();
    foreach ($permissions as $permission) {
      $permission->delete();
    }
  }

  public static function registerNewPermissions()
  {
    app()['cache']->forget('spatie.permission.cache');
    Schema::enableForeignKeyConstraints();
    $permissions = (new PermissionSeeder())->getPermissions();

    foreach ($permissions as $permission) {
      $perm = Permission::firstOrCreate(['name' => $permission['name']], $permission);
      $perm->update($permission);
    }
  }

  /**
   * Sincronizar permisos con los usuarios para que accedan a cualquier recurso nuevo disponible
   * 
   * @return void
   */
  public static function setPermissionsToUsers($all_user = 1)
  {
    $usuarios = $all_user ? User::all() : User::owners()->get();
    $permissionSeeder = new PermissionSeeder();
    $permissions = collect($permissionSeeder->getPermissions())->where('is_admin', false)->pluck('name');

    foreach ($usuarios as $usuario) {
      $usuario->setAllPermissions($permissions);
    }
  }
}
