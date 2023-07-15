<?php	

Route::prefix('grupos')->group(function(){

	Route::name('grupos.')->group(function(){

		Route::get('/index/{create?}','GruposController@index')->name('index');
		Route::get('search','GruposController@search')->name('search');
		Route::post('guardar','GruposController@guardar')->name('guardar');
		Route::post('editar','GruposController@editar')->name('editar');
		Route::post('delete','GruposController@eliminar')->name('borrar');


	});
	
});


