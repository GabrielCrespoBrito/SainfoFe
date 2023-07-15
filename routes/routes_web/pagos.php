<?php

Route::middleware('tenant.exists')->group(function () {

	Route::prefix('pagos')->group(function(){
	Route::name('pagos.')->group(function(){
			
		Route::post('check_pago','VentasPagosController@checkPago')->name('check_pago');

		# Guardar 
		Route::post('venta','VentasPagosController@savePago')->name('venta.store');

		# Guardar pagos
		Route::post('compra/', 'Compra\CompraPagoController@store')->name('compra.store');

		# Guardar pagos
		Route::post('compra/delete', 'Compra\CompraPagoController@delete')->name('compra.delete');

		# Actualizar Pagos
		Route::post('compra/update/{id?}', 'Compra\CompraPagoController@update')->name('compra.update');
		Route::post('venta/update/{id?}', 'VentasPagosController@update')->name('venta.update');


		# show
		Route::post('ventas/show/{id?}', 'VentasPagosController@show')->name('venta.show');

		# wwwwww
		Route::post('compras/show/{id?}', 'Compra\CompraPagoController@show')->name('compra.show');

	});
	});

});
