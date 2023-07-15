<?php

namespace App\Console\Commands;

use App\User;
use App\Permission;
use PermissionSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPermissions extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'system_task:add_permisos --{all_user=0}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Registrar Permisos Nuevos Si existen y agregarlos a los usuarios Principales';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }


  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    app()['cache']->forget('spatie.permission.cache');
    Schema::enableForeignKeyConstraints();

    $permissions_register = Permission::all()->pluck('name');
    $permissions_new_ = collect((new PermissionSeeder())->getPermissions());
    $permissions_new = $permissions_new_->pluck('name');

    
    //
    $permissions_add = $permissions_new->diff($permissions_register);
    $permissions_delete = $permissions_register->diff($permissions_new);
    
    if ( $permissions_add->count() === 0 && $permissions_delete->count() === 0) {
      return;
    }
    
    if($this->argument('all_user')){
      $users_group = User::get()->chunk(50);
    }
    else {
      $users_group = User::where('carcodi', User::TIPO_DUENO )->get()->chunk(50);
    }
    
    foreach ($permissions_add as $permission_add) {
      
      $permission_added = Permission::create([
        'name' => $permission_add,
        'descripcion' =>   __('messages.' . $permission_add),
        'group' => $permissions_new_->where('name', $permission_add)->first()['group'],
      ]);

      foreach ($users_group as $users) {
        foreach ($users as $user) {
          $user->givePermissionTo($permission_added);
        }
      }
    }


    // eliminar permisos
    DB::table(config('permission.table_names.permissions'))
      ->whereIn('name', $permissions_delete->toArray())
      ->delete();
  }
}
