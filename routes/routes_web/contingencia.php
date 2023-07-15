<?php 

Route::post('contingencia/{id}/update', 'Contingencia\ContingenciaController@update')->name('contingencia.update');
Route::post('contingencia/addItems', 'Contingencia\ContingenciaController@addItems')->name('contingencia.addItems');
Route::get('contingencia/{id?}/txt', 'Contingencia\ContingenciaController@generateTxt')->name('contingencia.txt');
Route::resource('contingencia', 'Contingencia\ContingenciaController', ['parameters' => ['contingencia' => 'id']])->except(['update']);
