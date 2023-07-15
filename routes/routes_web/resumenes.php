<?php

// Resumenes de boletas

Route::get('resumen/{id?}/txt', 'Resumen\ResumenDiarioController@txt')->name('resumen.txt');

Route::get('resumen/{id?}/validar', 'Resumen\ResumenDiarioController@validar')->name('resumen.validar');

Route::resource('resumen-diario', 'Resumen\ResumenDiarioController');