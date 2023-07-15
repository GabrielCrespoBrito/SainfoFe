<?php	

Route::prefix('nube')->group(function(){

	Route::name('nube.')->group(function(){

    Route::get('respaldo','NubeController@index')->name('index');
    Route::get('search_documentos','NubeController@search')->name('search_documentos');
    Route::post('respaldar_documento','NubeController@respaldar_documento')->name('respaldar_documento');


	});
	
});


