<?php 

use Illuminate\Support\Facades\Route;


// Route::resource('user-local', 'UserLocal\UserLocalController')->except(['show','edit','update']);

Route::get('user-local', 'UserLocal\UserLocalController@index')->name('user-local.index');
Route::get('user-local/create', 'UserLocal\UserLocalController@create')->name('user-local.create');

Route::post('user-local', 'UserLocal\UserLocalController@store')->name('user-local.store');
Route::post('user-local/consultLocals', 'UserLocal\UserLocalController@consultLocals')->name('user-local.consult_locals');

Route::get('user-local/{usucodi}/{loccodi}/edit', 'UserLocal\UserLocalController@edit')->name('user-local.edit');
Route::get('user-local/{usucodi}/{loccodi}/setDefault', 'UserLocal\UserLocalController@setDefaultLocal')->name('user-local.default');

Route::put('user-local/{usucodi}/{loccodi}', 'UserLocal\UserLocalController@update')->name('user-local.update');
Route::delete('user-local/{usucodi}/destroy', 'UserLocal\UserLocalController@destroy')->name('user-local.destroy');
