<?php 

Route::get('configuracion' , 'ConfiguracionController@index')->name('configuracion.index');
Route::post('configuracion/store','ConfiguracionController@store')->name('configuracion.store');

?>