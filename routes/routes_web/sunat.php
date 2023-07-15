<?php

// use Illuminate\Support\Facades\Route;

Route::post('sunat/verificar_pendientes', 'SunatController@verificar_pendientes')->name('sunat.verificar_pendientes');
Route::post('sunat/anular_documento', 'Sunat\SunatAnulacionController@make')->name('sunat.anular_documento');
Route::get('sunat/products', 'Sunat\SunatProductoController@search')->name('sunat.productos');
