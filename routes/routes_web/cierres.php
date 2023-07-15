<?php

Route::prefix('cierre')->group(function () {

  Route::name('cierre.')->group(function () {
    Route::get('/', 'Cierre\CierreController@index')->name('index');;
    Route::get('/search', 'Cierre\CierreController@search')->name('search');;
    Route::get('/toggle/{mescodi?}', 'Cierre\CierreController@toggle')->name('toggle');;
  });
});
