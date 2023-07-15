<?php 

Route::prefix('ubigeo')->group(function(){

	Route::get('ubigeosearch', 'ClienteProveedorController@ubigeosearch')->name('clientes.search');

});

