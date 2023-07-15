<?php

use Illuminate\Support\Facades\Route;

# Toma de inventario
Route::get('toma-inventario', 'TomaInventarioController@index')->name('toma_inventario.index');
Route::post('toma-inventario/update-stocks', 'TomaInventarioController@updateStocks')->name('toma_inventario.update-stocks');
Route::post('toma-inventario/exportExcellProducto', 'TomaInventarioController@exportExcellProducto')->name('toma_inventario.export_excell');
Route::post('toma-inventario/importExcellProducto', 'TomaInventarioController@importExcellProducto')->name('toma_inventario.import_excell');

Route::get('toma-inventario/create', 'TomaInventarioController@create')->name('toma_inventario.create');
Route::post('toma-inventario/store', 'TomaInventarioController@store')->name('toma_inventario.store');
Route::get('search', 'TomaInventarioController@search')->name('toma_inventario.search');
Route::get('toma-inventario/{id?}/show', 'TomaInventarioController@show')->name('toma_inventario.show');
Route::get('toma-inventario/{id?}/edit', 'TomaInventarioController@edit')->name('toma_inventario.edit');
Route::post('toma-inventario/{id?}/update', 'TomaInventarioController@update')->name('toma_inventario.update');
Route::delete('toma-inventario/{id?}/destroy', 'TomaInventarioController@destroy')->name('toma_inventario.destroy');
Route::get('toma-inventario/{id?}/pdf', 'TomaInventarioController@pdf')->name('toma_inventario.pdf');
