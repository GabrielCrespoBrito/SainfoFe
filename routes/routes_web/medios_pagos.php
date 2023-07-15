<?php


Route::get('medios-pagos', 'MedioPago\MedioPagoController@index')->name('medios_pagos.index');
Route::get('medios-pagos/{id?}', 'MedioPago\MedioPagoController@changeStatus')->name('medios_pagos.toggle_status');
Route::get('medios-pagos/{id?}/default/', 'MedioPago\MedioPagoController@setDefault')->name('medios_pagos.set_default');

