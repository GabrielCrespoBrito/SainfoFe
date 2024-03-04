<?php

Route::prefix('preventa')->group(function () {

  Route::name('coti.')->group(function () {
    Route::post('importar', 'CotizacionesController@getCotizacion')->name('importar');

    Route::get('index/{tipo}', 'CotizacionesController@index')
      ->name('index')
      ->where(['tipo' => '50|53|98']);

    Route::get('search', 'CotizacionesController@search')->name('search');
    Route::get('create', 'CotizacionesController@create')->name('create');
    Route::get('edit/{id_cotizacion}', 'CotizacionesController@edit')->name('edit');
    Route::post('anular-/{id}', 'CotizacionesController@anular')->name('anular');
    Route::post('update', 'CotizacionesController@update')->name('update');
    Route::delete('delete/{cotizacion_id}', 'CotizacionesController@delete')->name('delete');
    Route::post('save', 'CotizacionesController@save')->name('save');
    Route::post('liberar', 'CotizacionesController@liberar')->name('liberar');


    Route::get('reporte-create', 'CotizacionReportController@create')->name('report');
    Route::post('report-show', 'CotizacionReportController@report')->name('report-post');

    Route::get('imprimir/{tipo_impresion?}/{id_cotizacion?}/{formato?}', 'CotizacionesController@pdf')->name('imprimir');
  });
});



Route::prefix('tienda')->group(function () {

  Route::get('/', 'TiendaController@index')
    ->name('tienda.index');

  Route::post('indexTable', 'TiendaController@indexTable')
  ->name('tienda.indexTable');

  Route::get('{id}/destroy', 'TiendaController@destroy')
    ->name('tienda.destroy');


});
