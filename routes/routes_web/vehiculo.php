<?php

Route::get('vehiculo/search' , 'Vehiculo\VehiculoController@search')->name('vehiculo.search');


Route::resource('vehiculo' , 'Vehiculo\VehiculoController');

