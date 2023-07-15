<?php	

Route::get('compras/search','Compra\CompraController@search')->name('compras.search');
Route::post('compras/{id?}/paymentStatus', 'Compra\CompraPagoController@paymentStatus')->name('compras.paymentStatus');
# Ver información de pagos y saldo por pagar de una compra
Route::post('compras/{id?}/pago', 'Compra\CompraPagoController@payments')->name('compras.pagos');
Route::resource('compras','Compra\CompraController')->except(['update']);
Route::post('compras/{id?}/update','Compra\CompraController@update')->name('compras.update');
Route::resource('compras_item', 'Compra\CompraItemController')->only('store');
Route::get('compras/{id?}/pdf', 'Compra\CompraController@pdf')->name('compras.pdf');

// Orden de Compra
Route::resource('orden-compras', 'Compra\OrdenCompraController')
->names('orden_compras')
->except(['update', 'store']);


Route::get('orden-compras/searchImport', 'Compra\OrdenCompraController@search')
->name('orden_compras.search');

// Orden de Compra
Route::post('orden-compras', 'Compra\OrdenCompraController@save')
  ->name('orden_compras.store');

Route::get('orden-compras/{t_impresion?}/{id?}', 'Compra\OrdenCompraController@pdf')
->name('orden_compras.imprimir');