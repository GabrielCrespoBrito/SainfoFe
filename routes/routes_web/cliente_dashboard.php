<?php 

Route::namespace('ClienteAdministracion')->group(function(){

	Route::prefix('administracion')->group(function(){

		Route::name('cliente_administracion.')->group(function(){

			Route::get('cliente','ClienteDashboardController@index')->name('index');

			Route::get('perfil','ClienteDashboardController@perfil')->name('perfil');

			Route::get('buscar_documentos','ClienteDashboardController@search_documentos')->name('buscar_documentos');

			Route::post('descargar_files' , 'ClienteDashboardController@descargar_files')->name('descargar_files');

			Route::post('update_password' , 'ClienteDashboardController@update_password')->name('update_password');

		});
	});
});

?>


