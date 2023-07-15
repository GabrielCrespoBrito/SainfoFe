<?php	

Route::resource('contratas_entidad', 'ContrataEntidadController');

Route::get('contratas_entidad/{id?}/pdf', 'ContrataEntidadController@pdf')->name('contratas_entidad.pdf');

Route::post('contratas_entidad/{id?}/send_email', 'ContrataEntidadController@sendEmail')->name('contratas_entidad.send_email');




