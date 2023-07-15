<?php

Route::prefix('banco')->group(function () {

  Route::name('banco.')->group(function () {
    Route::get('/', 'Banco\BancoController@index')->name('index');
    Route::post('apertura', 'Banco\BancoController@apertura')->name('apertura');
    Route::post('reaperturar', 'Banco\BancoController@reaperturar')->name('reaperturar');
    Route::post('cerrar', 'Banco\BancoController@cerrar')->name('cerrar');
    Route::post('destroy', 'Banco\BancoController@destroy')->name('destroy');
    Route::get('search', 'Banco\BancoController@search')->name('search');
  });
});

# Cuenta
Route::resource('cuenta', 'Cuenta\CuentaBancariaController');
Route::get('cuenta/{cuenta_id?}/reporte-tipo-pago/{tipo?}', 'Reportes\CuentaPagoReportController@pdf')->name('cuenta.reporte_tipo_pago');