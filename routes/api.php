<?php


Route::post('login', 'ModuloApi\AuthController@login');

Route::post('consultarDocumento', 'ModuloApi\DocumentConsultController@getStatus');

Route::post('consultarDocumento', 'ModuloApi\DocumentConsultController@getStatus');

Route::get('/consult-documento/{ruc}/', "Documento\DocumentoApiConsultController@consult")->name('busquedaDocumentos.api');

