<?php

use Illuminate\Support\Facades\Route;

Route::middleware('tenant.exists')->group(function () {
  Route::prefix('importar')->group(function(){
    Route::name('importar.')->group(function(){

      Route::get('file-plantilla/{tipo?}', 'Import\ImportVentasController@getPlantilla')
      ->name('plantilla')
      ->where('tipo', 'ventas|productos|clientes');


      # ------------------- Ventas  ------------------- 
      Route::get('ventas', 'Import\ImportVentasController@create')->name('ventas.create');
      Route::post('ventas', 'Import\ImportVentasController@store')->name('ventas.store');
      
      # ------------------- Clientes  ------------------- 
      Route::get('clientes', 'Import\ImportClientesController@create')->name('clientes.create');
      Route::post('clientes', 'Import\ImportClientesController@store')->name('clientes.store');


      Route::get('productos', 'Import\ImportProductosController@create')->name('productos.create');
      Route::post('productos', 'Import\ImportProductosController@store')->name('productos.store');


    });
  });
});