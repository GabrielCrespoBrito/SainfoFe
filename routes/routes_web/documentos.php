<?php

  Route::get('documentos/search' , 'DocumentosController@search')->name('documentos.search');
  Route::get('documentos/subir' , 'DocumentosController@subirDocumentos')->name('documentos.subir');
  Route::post('documentos/upload' , 'DocumentosController@uploadDocumentos')->name('documentos.upload');
  Route::post('documentos/uploadSingle' , 'DocumentosController@uploadDocumento')->name('documentos.uploadSingle');
  Route::get('documentos/pendientes/{tipo_documento}/{lapso}' , 'DocumentosController@index')->name('documentos.pendientes');
  Route::post('documentos/download' , 'DocumentosController@downloadDocumentos')->name('documentos.download');