<?php	

Route::prefix('cajas')->group(function(){

	Route::name('cajas.')->group(function(){

		Route::get('ver/{id_caja?}/movimientos/{tipo_movimiento?}','CajasController@movimientos')
		->name('movimientos')    
		->where(['tipo_movimiento' => 'ingresos|egresos']);

    Route::get('search-movimientos/{id_caja?}/{tipo_movimiento?}', 'CajasController@searchMovimientos')
    ->name('search_movimientos')
    ->where(['tipo_movimiento' => 'ingresos|egresos']);

		Route::post('totals/{is_por_pagar?}/totals','CajasController@getTotals')->name('totales');


		Route::get('cuentas/{type?}/{id_cliente?}','CajasController@cuentas')
		->name('cuentas')
		->where(['type' => 'por_pagar|por_cobrar']);    

		Route::get('cuentas_search/{type?}/search','CajasController@cuentasporpagar_search')
		->name('cuentasporpagar_search')
		->where(['type' => 'por_pagar|por_cobrar']);

		Route::get('cuentas_pdf/{type?}','CajasController@cuentasporpagar_pdf')
		->name('cuentas_pdf')
		->where(['type' => 'por_pagar|por_cobrar']);
    
		// cuentasporpagar_pdf
		// --------------------------------------------------------------

		Route::get('index','CajasController@index')->name('index');
		Route::get('search','CajasController@search')->name('search');
		Route::get('resumen/{caja_id}','CajasController@resumen')->name('resumen');

		Route::post('aperturar','CajasController@aperturar')->name('aperturar');
		Route::post('reaperturar','CajasController@reaperturar')->name('reaperturar');        
		Route::post('editar','CajasController@editar')->name('editar');

		Route::post('create_movimiento','CajasController@create_movimiento')->name('create_movimiento');    
		Route::get('ingresos','CajasController@ingresos')->name('ingresos');
		Route::get('egresos','CajasController@egresos')->name('egresos');
		Route::post('cerrar','CajasController@cerrar')->name('cerrar');
		Route::post('borrar','CajasController@borrar')->name('borrar');
		Route::post('borrar_movimiento','CajasController@borrar_movimiento')->name('borrar_movimiento');

    // Motivos
		Route::get('motivos/show/{type?}','MotivosController@index')->name('motivos_show');
		Route::post('motivos/save/{type?}','MotivosController@save')->name('motivos_save');
    Route::get('motivos/consult/{type?}', 'MotivosController@search')->name('motivos_search');
		Route::post('motivos/edit/{type?}','MotivosController@edit')->name('motivos_edit');
		Route::post('motivos/delete/{type?}','MotivosController@delete')->name('motivos_delete');
		//

		Route::post('detalles/{id_detalle?}','CajaDetallesController@show')->name('detalle.show');
		Route::post('ver/{id_caja?}/dinero_aperturar','CajasController@dinero_apertura')->name('dinero_apertura');
		Route::post('movs/{caja_id?}/ingresoStore','CajasController@ingresos_create')->name('ingreso_store');		
		Route::post('movs/{caja_id?}/ingresoUpdate/{id_movimiento?}','CajasController@ingresos_update')->name('ingreso_update');		
		Route::post('ver/{id_caja?}/movimiento/egresos/create','CajasController@egresos_create')->name('egresos_create');
		Route::post('ver/{id_caja?}/movimiento/egresos/edit','CajasController@egresos_edit')->name('egresos_edit');
		Route::post('reporte/{id_caja?}','CajasController@reporte')->name('resumen_pdf');
    Route::get('reporte-simplificado/{id_caja?}/{formato?}', 'CajasController@reporteSimplificado')->name('resumen_simplificado_pdf');



    

    Route::get('imprimir-egreso/{id?}', 'CajaDetallesController@pdf')->name('imprimir_egreso');

		Route::get('reporteDetallado/{id_caja?}/{tipo?}', 'CajasController@reporteDetallado')->name('resumen_pdf_detallado');

		Route::get('reporteDocumentos/{id_caja?}/{tipo?}/{agrupacion?}', 'CajasController@reporteVenta')
		->name('reporte_documento')
		->where(['tipo' => 'ventas|compras', 'agrupacion' => 'tipo_documento|medio_pago' ]);
    
		Route::post('consultar_movimiento/{id_caja?}/{id_tipo_movimiento?}','CajasController@consultar_movimiento')->name('consultar_movimiento');
	
	});

});




