<?php 

Route::prefix('clientes')->group(function(){

	Route::get('clientes/{accion?}/{id_ruc?}', 'ClienteProveedorController@index')->name('clientes.index')->where(['accion' => 'create|edit']);

	Route::post('clientes/create', 'ClienteProveedorController@create')->name('clientes.create');

	// Registrar cliente solo con el documento o el nombre
	Route::post('clientes/register', 'ClienteProveedorController@register')->name('clientes.store_simple');
	
	Route::post('clientes/edit', 'ClienteProveedorController@edit')->name('clientes.edit');		

	Route::get('clientes/search', 'ClienteProveedorController@search')->name('clientes.consulta');

	Route::get('ubigeosearch', 'Ubigeo\UbigeoController@ubigeosearch')->name('clientes.ubigeosearch');

	Route::get('clientes/searchCliente','ClienteProveedorController@searchByCliente')->name('clientes.consultaCliente');		
	
	Route::post('clientes/consultar_ruc', 'ClienteProveedorController@ruc_busqueda')->name('clientes.consulta_ruc');

	Route::post('clientes/consultar_codigo', 'ClienteProveedorController@consulta_codigo')->name('clientes.consulta_codigo');

	Route::post('clientes/eliminar', 'ClienteProveedorController@eliminar')->name('clientes.eliminar');
	Route::post('clientes/restaurar', 'ClienteProveedorController@restaurar')->name('clientes.restaurar');

	Route::post('clientes/consultar_datos', 'ClienteProveedorController@consultar_datos')->name('clientes.consultar_datos');	

	Route::post('clientes/buscar_cliente', 'ClienteProveedorController@buscar_cliente')->name('clientes.buscar_cliente');

	Route::get('searchcliente/select2', 'ClienteProveedorController@buscar_cliente_select2')->name('clientes.buscar_cliente_select2');

	Route::get('ventas/search', 'ClienteProveedorController@searchByCliente')->name('clientes.ventas.search');

	Route::post('departamento/consulta', 'Departamento\Departamento@departamentoConsulta')->name('consulta.departamento');

	Route::get('actividad/clientes', 'Actividad\ActividadController@actividad')->name('clientes.actividad');
	Route::get('actividad/search', 'Actividad\ActividadController@actividad_search')->name('clientes.actividad_search');

	#
	Route::post('consult-dni', 'ClienteProveedor\ConsultDocumentController@consultDNI')->name('clientes.consult_dni');
	Route::post('consult-ruc', 'ClienteProveedor\ConsultDocumentController@consultRUC')->name('clientes.consult_ruc');

});

Route::get('proveedor/search', 'Proveedor\ProveedorController@search')->name('proveedor.search');		