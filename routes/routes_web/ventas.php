<?php	


Route::middleware('tenant.exists')->group(function () {

	Route::prefix('ventas')->group(function(){
	Route::name('ventas.')->group(function(){
    
		// ventas/index
		Route::get('/', 'VentasController@index')->name('index');

		// ventas/search
		Route::get('search', 'VentasController@search')->name('consulta');	

		Route::get('search-canje', 'VentasController@searchCanje')->name('search_canje');	

		// ventas/searchCliente
		Route::get('searchCliente', 'TipoDocumentoPagoController@searchCliente')->name('consultaCliente');

		// ventas/tipodocumento
		Route::get('tipodocumento/search','TipoDocumentoPagoController@search')->name('tipo_documento');	

		// ventas/seriedocumento
		Route::get('seriedocumento/','TipoDocumentoPagoController@serie_consultar')->name('verificar_serie');

		// ventas/tipodocumentoselect
		Route::post('tipodocumento/','TipoDocumentoPagoController@busqueda')->name('tipo_documento_select');
    
    Route::post('prev/', 'VentaPreviewController@create')->name('prev');
    
    
    Route::post('data_impresion/', 'VentaImprecionDirectaController@show')->name('data_impresion');

      


		Route::post('verificar_ticket/','VentasController@verificar_ticket')->name('verificar_ticket');

		// ventas/search
		Route::get('nueva_factura/', 'VentasController@create_factura')->name('nueva_factura');

		// ventas/consultar
		Route::post('consultar-sunat/{id?}/{cdr?}', 'Sunat\DocumentConsultStatusController@consult' )->name('consult_sunat');

    // ventas/consultar/anular
    Route::post('anular-nota-venta/{id?}', 'VentasController@anularNotaVenta')->name('anular_nv');

    //  
    Route::get('getStatusCDR/{id?}', 'Sunat\DocumentConsultStatusController@consultCDR')->name('consult_sunat_cdr');

		# Obtener toda la información
		Route::post('informacion/{id}', 'VentasController@getInformacion')->name('informacion');
    
    Route::get('download-files/{id}', 'VentasController@getFiles')->name('descargar_recursos');

		// ventas/ver_factura
		Route::get('ver_factura/{id_factura}', 'VentasController@show')->name('show');

    # ventas/ver_factura
    Route::get('showApi/{id}/{nc}', 'VentasController@showApi')->name('showApi');
    # Crear Nota de Credito
    Route::post('createNC/{id}', 'VentasController@createNC')->name('createNC');
    # Crear Nota de Debito
    Route::post('createND/{id}', 'VentasController@createND')->name('createND');
      
		// ventas/modificar_factura
		Route::get('modificar_factura/{id_factura}', 'VentasController@edit')->name('edit');


		// ventas/modificar_factura
		Route::get('modificar_factura/{id_factura?}', 'VentasController@modificar_factura')->name('modificar_factura');

		Route::post('verificar_item','VentasController@verify_item')->name('verificar_item');

		Route::post('verificar_factura','VentasController@saveFactura')->name('verificar_factura');

		// ventas/imprimirFactura
		Route::get('imprimirFactura/{id_factura?}/{formato?}/{download?}','VentasController@imprimirFactura')->name
		('imprimirFactura');

		# Loremp
		Route::get('verXml/{id_factura}','VentasController@verXml')->name('ver_xml');

		Route::get('accion-validacion/{id}','VentasController@accionValidacion')->name('accion_sunat');

    // Recursos de la anulacion
    Route::get('recurso-anulacion/{id}/{tipo?}', 'VentasController@recursoAnulacion')->name('recurso_anulacion');


		Route::get('documento-anticipo','VentasController@searchDocumentoAnticipo')->name('documento_anticipo');

		# Loremp
		Route::get('verCdr/{id_factura}','VentasController@verCdr')->name('ver_cdr');

		##################### PAGOS #####################

		// ventas/check_pago
		Route::post('check_pago','VentasPagosController@checkPago')->name('check_pago');

		// ventas/check_deudas
		Route::post('check_deudas','VentasController@checkDeudas')->name('check_deudas');

		# Guardar pagos
		Route::post('save_pago','VentasPagosController@savePago')->name('save_pago');

		# Eliminar Pagos
		Route::post('quitar_pago/{type?}','VentasPagosController@removePago')->name('quitar_pago');

		// ventas/quitar_pago
		Route::post('data_pago','VentasPagosController@dataPago')->name('data_pago');
		
		# Ver información de pagos y saldo por pagar de una venta
		Route::post('pagos/','VentasPagosController@pagos')->name('pagos');

		#
		Route::post('ventas', 'VentasPagosController@paymentStatus')->name('paymentStatus');
    
    #
    Route::get('nota-creditos/{venta?}', 'NotaCreditoController@searchToPay')->name('nota_credito.topay');


    ##################### PAGOS #####################

		# Guia

		// ventas/saveguia
		Route::post('saveguia','GuiaSalidaController@save')->name('saveguia');

		// ventas/enviar_sunat_factura
		Route::post('send_sunat_factura','SunatController@send_sunat')->name('send_sunat');

		// ventas eliminar
		Route::post('eliminar','VentasController@delete')->name('eliminar');

		// ventas pendientes
		Route::get('pendientes/{tipo?}','VentasController@pendientes')->name('pendientes');

		// search_pendientes
		Route::get('pendientes_consulta','VentasController@search_pendientes')->name('consulta_pendientes');

		// -------------- nube respaldo --------------
		Route::get('respaldo','NubeController@index')->name('respaldo');

		
	});
	});
	


});