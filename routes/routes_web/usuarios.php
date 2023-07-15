<?php 

use Illuminate\Support\Facades\Route;

Route::prefix('usuarios')->group(function(){

  Route::get('/', "UsersController@index" )
	->name('usuarios.index');
  
  Route::get('mantenimiento', "UsersController@mantenimiento" )
	->name('usuarios.mantenimiento');

  // --------- Perfil ---------

  # Formulario del perfil
  Route::get('perfil', "User\PerfilController@show" )
	->name('usuarios.perfil');

  # Form Ajax
  Route::post('form/{id?}', "UsersController@showForm")
  ->name('usuarios.form');

  # Actualizar perfil
  Route::post('perfil', "User\PerfilController@update" )
	->name('usuarios.perfil.update');

  // --------- Cambiar contraseña ---------

  Route::post('change-password', "User\ChangePasswordController@update" )
	->name('usuarios.password.update');

  
  Route::get('create', "UsersController@create" )
	->name('usuarios.create');

  Route::get('consulta', "UsersController@search" )
	->name('usuarios.search');

  Route::get('assign_role/{id_user}', "UsersController@roles" )
  ->name('usuarios.assign_role');
  Route::post('assign_role/{id_user}', "UsersController@roles_store" )
  ->name('usuarios.assign_role_store');




  // Seleccionar empresa - borrar pasadao
  Route::get('seleccionar_empresa/{id_user?}/{id_empresa?}', "UsersController@seleccionar_empresa" )
  ->name('usuarios.empresa.create');
  Route::post('seleccionar_empresa', "UsersController@store_empresa" )
  ->name('usuarios.empresa.store');
  Route::get('show_empresa/{id?}', "UsersController@empresa_show" )
  ->name('usuarios.empresa.show');

  Route::get('asignar_documento/{id_empresa?}/{id_user?}/{id_local?}', "UsersController@createDocumento" )
  ->name('usuarios.asignar_documento.create');

  Route::post('asignar_documento/', "UsersController@storeDocumento" )
  ->name('usuarios.asignar_documento.store');

  Route::post('delete_empresa/{id}', "UsersController@empresa_delete" )
  ->name('usuarios.empresa.delete');

  Route::post('borrar/{id}', "UsersController@delete" )
  ->name('usuarios.borrar');

  Route::delete('delete/{id}', "UsersController@delete")
  ->name('usuarios.destroy');

  Route::post('toggle_active/{id}', "UsersController@activeToggle" )
  ->name('usuarios.toggleActiveEstate');

  Route::get('cambiar-status/{id}', "UsersController@cambiarStatus")
  ->name('usuarios.cambiar-status');


  // borrar
  Route::post('store', "UsersController@store" )->name('usuarios.store');

  Route::post('store-owner', "UsersController@storeOwner" )->name('usuarios.store.owner');

  // borrrar
  Route::post('update', "UsersController@update" )->name('usuarios.update');

  Route::post('update-owner', "UsersController@updateOwner" )->name('usuarios.update.owner');

  Route::post('check_priv', "UsersController@CheckPriv" )->name('usuarios.check');

  # Permisos del usuario
  Route::get('permisos/{id_user}', "UsersController@permisos")
  ->name('usuarios.asignar_permisos');

  Route::post('permisos/{id_user}', "UsersController@storePermisos")
  ->name('usuarios.store_permisos');


  Route::get('documentos/mantenimiento', "UsersController@createDocumento" )
  ->name('usuarios.asignar_documento.create');

});