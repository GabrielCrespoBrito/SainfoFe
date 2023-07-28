<?php 

Route::prefix('ubigeo')->group(function(){

	Route::get('ubigeosearch', 'Ubigeo\UbigeoController@ubigeosearch')->name('ubigeo.search');

});

