<?php

Route::get('/searchMeses', 'MesController@index')->name('meses_search');
Route::get('/searchGrupos', 'GruposController@searchApi')->name('grupos_search');
Route::get('/searchMarcas', 'MarcasController@searchApi')->name('marcas_search');
Route::get('/searchAlmacenes', 'LocalController@searchApi')->name('locales_search');
