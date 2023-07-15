<?php

namespace App\Jobs\User;

use App\Permission;
use App\User;

class RegisterPermission
{
  public $user;
  public $permissions;

  public function __construct(User $user,  $permissions)
  {
    $this->user = $user;
    $this->permissions = $permissions;
  }

  public function handle()
  {
    foreach( $this->permissions as $permission ){

      $permissions = Permission::where('group', $permission)
      ->get()
      ->pluck('id')
      ->toArray();

      $this->user->givePermissionTo($permissions);
    }
  }
}
