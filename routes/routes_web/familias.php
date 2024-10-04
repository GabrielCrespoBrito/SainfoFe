<?php	

Route::prefix('familias')->group(function(){

	Route::name('familias.')->group(function(){

		Route::get('index/{create?}','FamiliasController@index')->name('index');

		Route::get('search','FamiliasController@search')->name('search');

		Route::post('guardar','FamiliasController@guardar')->name('guardar');

		Route::post('editar','FamiliasController@editar')->name('editar');

		Route::post('borrar','FamiliasController@borrar')->name('borrar');

    Route::get('revert/{id?}/{id_grupo?}','FamiliasController@restaurar')->name('restaurar');

	});
	
});


