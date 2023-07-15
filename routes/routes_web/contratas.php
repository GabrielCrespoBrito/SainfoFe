<?php	

Route::resource('contratas', 'ContratasController');
Route::get('contratas/{id?}/pdf', 'ContratasController@pdf')->name('contratas.pdf');


