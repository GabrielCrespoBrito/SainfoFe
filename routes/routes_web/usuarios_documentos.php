<?php 

Route::prefix('usuarios_documentos')->group(function(){

  Route::get('mantenimiento', "UsersDocumentosController@index" )
	->name('usuarios_documentos.mantenimiento');

  Route::post('test-print', "UsersDocumentosController@testPrintDirect")
  ->name('usuario_documento.test_print');

  Route::get('search', "UsersDocumentosController@search")
	->name('usuarios_documentos.search');

  Route::get('create/{id_empresa?}/{id_user?}', "UsersDocumentosController@create" )->name('usuarios_documentos.create');

  Route::post('store', "UsersDocumentosController@store" )->name('usuarios_documentos.store');

  Route::get('edit/{id}', "UsersDocumentosController@edit" )->name('usuarios_documentos.edit');  

  Route::put('update/{id}',"UsersDocumentosController@update")->name('usuarios_documentos.update');  
  Route::post('delete/{id}',"UsersDocumentosController@delete")->name('usuarios_documentos.delete');  


});




