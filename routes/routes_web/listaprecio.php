<?php	

Route::resource('lista-precios', 'ListaPrecio\ListaPrecioController', [
	'names' => 'listaprecio',
	'parameters' => ['lista-precios' => 'id'],
]);


Route::get('lista-precios/{id}/toggleLimitPrecMini/{limit?}', 'ListaPrecio\ListaPrecioController@cambiarLimitMinimo')->name('listaprecio.toggleLimit');
// 'names' => 'listaprecio',
//   'parameters' => ['lista-precios' => 'id'],
// ]);



// Route::get('lista-precios/{id}/chambitLimite/{limit?}', 'ListaPrecio\ListaPrecioController@cambiarLimitMinimo', [
// 'names' => 'listaprecio',
//   'parameters' => ['lista-precios' => 'id'],
// ]);
