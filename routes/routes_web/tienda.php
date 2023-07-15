<?php

Route::prefix('tienda')->group(function(){
  Route::name('tienda.')->group(function(){
    Route::get('orden/{id?}/generar-cotizacion', 'Tienda\OrdenController@generarCotizacion')->name('orden.generar-cotizacion');
    Route::get('orden/search', 'Tienda\OrdenController@search')->name('orden.search');
    Route::get('orden/search', 'Tienda\OrdenController@search')->name('orden.search');
    Route::resource('orden', 'Tienda\OrdenController', ['names' => 'orden']);
  });
});

