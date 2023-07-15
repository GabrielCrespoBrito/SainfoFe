<?php

use Illuminate\Support\Facades\Route;

# Guia Salida
Route::prefix('guia')->group(function(){
  
  Route::name('guia.')->group(function(){

    Route::get('prepararGuias/{mescodi?}' , 'GuiaSalidaController@prepareGuias')->name('prepara_guias');
    Route::get('save-validacion/{guia_id?}' , 'GuiaSalidaController@saveSuccessValidacion')->name('validacion');
    Route::get('index' , 'GuiaSalidaController@index')->name('index');
    Route::get('search' , 'GuiaSalidaController@search')->name('search');
    Route::get('searchJson' , 'GuiaSalidaController@searchJson')->name('searchJson');
    Route::get('pendientes' , 'GuiaSalidaController@pendientes')->name('pendientes');
    Route::get('search_pendientes' , 'GuiaSalidaController@search_pendientes')->name('search_pendientes');
    Route::get('create/{id_venta?}' , 'GuiaSalidaController@create')->name('create');
    Route::get('edit/{id?}' , 'GuiaSalidaController@edit')->name('edit');
    // Creacion de guia desde la venta de manera simplificada
    Route::post('/{id?}/create-simply', 'GuiaSalidaController@createSimply')->name('create.simply');
    Route::post('/{id?}/store-simply', 'GuiaSalidaController@storeSimply')->name('store.simply');
    Route::post('traslado/{id?}', 'GuiaSalidaController@traslado')->name('traslado');    
    Route::redirect('show/{id?}', 'guia/edit{id}?')->name('show');    
    Route::post('store' , 'GuiaSalidaController@store')->name('store');
    Route::get('anular/{guia_id?}' , 'GuiaSalidaController@anularGuia')->name('anular');
    Route::post('update/{id?}' , 'GuiaSalidaController@update')->name('update');
    Route::delete('{id?}/delete' , 'GuiaSalidaController@delete')->name('delete');
    Route::post('{id?}/despacho' , 'GuiaSalidaController@despacho')->name('despacho');
    Route::post('sentSunat/{id?}','GuiaSalidaController@sentSunat')->name('sentsunat');
    Route::post('consultTicket/{id?}', 'GuiaSalidaController@consultTicket')->name('consultTicket');
    Route::get('pdf/{id?}/{formato?}' , 'GuiaSalidaController@pdf')->name('pdf');
    Route::get('file/{type?}/{id?}' , 'GuiaSalidaController@file')->name('file');
    Route::post('sent_email/{id?}' , 'GuiaSalidaController@sentEmail')->name('sent_email');
    Route::post('verificar/{id_guia?}' , 'GuiaSalidaController@verificar')->name('verificar');
    Route::get('reporte' , 'GuiaSalidaController@reporte')->name('reporte');
    Route::post('store/ingreso/{id?}' , 'Guia\GuiaController@storeIngreso')->name('store.ingreso');
    // Generar compra y venta a partir de la guia
    Route::post('generar_doc/{id?}' , 'Guia\GuiaController@generarDoc')->name('generar_doc');

  });    
});

# Guia Ingreso
Route::prefix('guia-ingreso')->group(function () {
  Route::name('guia_ingreso.')->group(function () {
    Route::get('index', 'Guia\GuiaIngresoController@index')->name('index');
    Route::get('search', 'Guia\GuiaIngresoController@search')->name('search');
    Route::get('create', 'Guia\GuiaIngresoController@create')->name('create');
    Route::post('store', 'Guia\GuiaIngresoController@store')->name('store');
    Route::get('edit/{id?}', 'Guia\GuiaIngresoController@edit')->name('edit');
    Route::post('update/{id?}', 'Guia\GuiaIngresoController@update')->name('update');
    Route::post('{id?}/despacho', 'Guia\GuiaIngresoController@despacho')->name('despacho');
    // Guardar observacion de una guia de ingreso de traslado
    Route::post('{id?}/conformidad', 'Guia\GuiaIngresoController@saveConformidad')->name('save_conformidad');
  });
});

# Guia Ingreso
Route::prefix('guia-traslado')->group(function () {
  Route::name('guia_traslado.')->group(function () {
    Route::get('index', 'Guia\GuiaTrasladoController@index')->name('index');
    Route::get('search', 'Guia\GuiaTrasladoController@search')->name('search');
    Route::get('create', 'Guia\GuiaTrasladoController@create')->name('create');
    Route::post('store', 'Guia\GuiaTrasladoController@store')->name('store');
    Route::get('edit/{id?}', 'Guia\GuiaTrasladoController@edit')->name('edit');
    Route::post('update/{id?}',   'Guia\GuiaTrasladoController@update')->name('update');
    Route::post('{id?}/despacho','Guia\GuiaTrasladoController@despacho')->name('despacho');
  });
});

# Guia Transportista
Route::prefix('guia-transportista')->group(function () {
  Route::name('guia_transportista.')->group(function () {
    Route::get('index', 'Guia\GuiaTransportistaController@index')->name('index');
    Route::get('search', 'Guia\GuiaTransportistaController@search')->name('search');
    Route::get('create', 'Guia\GuiaTransportistaController@create')->name('create');
    Route::post('store', 'Guia\GuiaTransportistaController@store')->name('store');
    Route::get('edit/{id?}',      'Guia\GuiaTransportistaController@edit')->name('edit');
    Route::post('update/{id?}',   'Guia\GuiaTransportistaController@update')->name('update');
    Route::post('{id?}/despacho', 'Guia\GuiaTransportistaController@despacho')->name('despacho');
  });
});
