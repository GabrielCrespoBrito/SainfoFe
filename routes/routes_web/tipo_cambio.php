<?php

// Route::post('tipo_cambio/updatedActual', 'TipoCambio\TipoCambioController@updatedToday')->name('tipo_cambio.updatedToday');
Route::post('tipo_cambio//updatedToday', 'TipoCambio\TipoCambioController@updatedToday')->name('tipo_cambio.updatedToday');


Route::resource('tipo_cambio', 'TipoCambio\TipoCambioController');
Route::post('current-tc/', 'TipoCambio\TipoCambioController@currentTC')->name('tipo_cambio.current');


