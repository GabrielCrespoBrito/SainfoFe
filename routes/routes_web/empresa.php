<?php

Route::prefix('empresa')->group(function () {

	Route::name('empresa.')->group(function () {

		Route::get('create', "Empresa\EmpresaController@create")->name('create');
		Route::post('store', "Empresa\EmpresaController@store")->name('store');
		Route::get('index', "Empresa\EmpresaController@index")->name('index');
		Route::get('search', "Empresa\EmpresaController@search")->name('search');

		Route::get('mantenimiento', "Empresa\EmpresaController@index")->name('mantenimiento');
    
    # Actualizar para el administrador
    Route::get('edit/{id}', "Empresa\EmpresaController@edit")->name('parametros');

    # Actualizar información principal
    Route::post('{id?}/update-main', "Empresa\EmpresaController@updateDataBasic")->name('update_basic');

    # Actualizar información sunat
    Route::post('{id?}/update-sunat', "Empresa\EmpresaController@updateSunat")->name('update_sunat');

    # Actualizar parte visual
    Route::post('{id?}/update-visual', "Empresa\EmpresaController@updateVisual")->name('update_visual');     

    # Actualizar parametros
		Route::post('{id}/update_parametros', "Empresa\EmpresaController@update_parametros")->name('update_parametros');

    # Actualizar parametros
    Route::post('{id}/update_modulos', "Empresa\EmpresaController@update_modulos")->name('update_modulos');


    # SUBIR CERTIFICADO		
		Route::get('subir_certificado/{id}', "Empresa\EmpresaController@subirCertificado")->name('subirCertificado');
		Route::post('subir_certificado/{id}', "Empresa\EmpresaController@storeCertificado")->name('storeCertificado');
		Route::post('check_certificado/{id}', "Empresa\EmpresaController@checkCertificado")->name('checkCertificados');
  
		Route::get('{id}/actualizarUso', "Empresa\EmpresaController@updateUsos")->name('usos.update');
		Route::delete('{id_empresa}/deleteLogo/{logo?}', "Empresa\EmpresaController@deleteLogo")->name('deleteLogo');

    # ELIMINAR
		Route::post('{id}/delete', "Empresa\EmpresaController@delete")->name('delete');

    # UTILITARIOS
    Route::get('update-productos-precios', "Empresa\EmpresaController@updatePreciosProductos")->name('update_productos_precios');    
		Route::get('update-valor-venta', "Empresa\EmpresaController@updateValorVenta")->name('update_valor_venta');
    Route::get('update-costos-reales/{id?}', "Empresa\EmpresaController@updateCostosReales")->name('update_costos_reales');

		Route::put('update-parametro/{id}', "Empresa\EmpresaController@updateParametroBasic")->name('update_parametros_basic');
    Route::post('generate-plantilla/{empresa_id?}/{plantilla_id?}', "PDFPlantillaController@generate")->name('generate_plantilla_pdf');
    
	});
});