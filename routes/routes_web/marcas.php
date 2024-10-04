<?php


Route::middleware('tenant.exists')->group(function () {

	Route::prefix('marcas')->group(function(){

		Route::name('marcas.')->group(function(){

			Route::get('index/{create?}','MarcasController@index')->name('index');
			Route::get('search','MarcasController@search')->name('search');
			Route::post('guardar','MarcasController@guardar')->name('guardar');
			Route::post('editar','MarcasController@editar')->name('editar');
			Route::post('borrar','MarcasController@eliminar')->name('borrar');
			Route::get('revert/{id}','MarcasController@restaurar')->name('restaurar');
		});
		
	});

});