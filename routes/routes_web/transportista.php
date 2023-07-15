<?php

Route::get('transportista/search' , 'Transportista\TransportistaController@search')->name('transportista.search');

Route::resource('transportista' , 'Transportista\TransportistaController');