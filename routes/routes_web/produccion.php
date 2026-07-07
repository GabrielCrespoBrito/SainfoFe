<?php

use Illuminate\Support\Facades\Route;


Route::middleware('modulo_activo:modulo_produccion_manual')->group(function(){
  # Toma de inventario
  Route::get('produccion/pdf', 'ProduccionController@pdf')->name('produccion.pdf');
  Route::resource('produccion', 'ProduccionController', ['name'=> 'produccion']);
  Route::post('produccion/{id}/cambiarEstado', 'ProduccionController@cambiarEstado')->name('produccion.cambiarEstado');

});
