<?php

Route::resource('vendedores', 'VendedorController')->names('vendedor');
Route::get('vendedores/{id?}/restaurar', 'VendedorController@restaurar')->name('vendedor.restaurar');
Route::get('vendedores/{id?}/destroy', 'VendedorController@destroy')->name('vendedor.destroy'); 
