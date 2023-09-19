<?php 

use Illuminate\Support\Facades\Route;

Route::prefix('sire')->group(function(){

	Route::get('/', 'SireController@index')->name('clientes.index');

});
