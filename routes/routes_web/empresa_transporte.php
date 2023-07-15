<?php


Route::get('empresa-transporte/search' , 'EmpresaTransporte\EmpresaTransporteController@search')->name('empresa_transporte.search');

Route::resource('empresa-transporte' , 'EmpresaTransporte\EmpresaTransporteController',
['names' => 'empresa_transporte']);

