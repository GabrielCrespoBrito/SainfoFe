<?php

  Route::name('landing.')->group(function () {
    # Pagina de inicio
    Route::get('/', 'Landing\LandingPageController@index')->name('index');
    Route::get('contabilidad', 'Landing\LandingPageController@contabilidad')->name('contabilidad');
    Route::get('contacto', 'Landing\LandingPageController@contact')->name('contact');
    Route::post('contacto', 'Landing\LandingPageController@send')->name('contact_send');
  });


  Route::get('/medios-contacto', "ContactoController@showForm")->name('user.contacto');

