<?php

use Illuminate\Support\Facades\Route;

Route::middleware([ 'auth' , 'administrative_user' ])->group(function () {  
  Route::prefix('admin')->group(function () {
    Route::name('admin.')->group(function () {

      # Home
      Route::get('/', 'Admin\HomeController@index')->name('home');

      # Documentos 
      Route::post('/documentos/delete/{id?}', 'Admin\DocumentoController@delete')->name('documentos.delete');
      Route::post('/documentos/delete-pdf/{id?}/{create?}', 'Admin\DocumentoController@deletePdf')->name('documentos.delete_pdf');
      Route::get('/documentos', 'Admin\DocumentoController@index')->name('documentos.index');
      Route::get('/documentos/pending', 'Admin\DocumentoController@pending')->name('documentos.pending');
      Route::get('/documentos/search', 'Admin\DocumentoController@search')->name('documentos.search');
      Route::get('/documentos/search-pending', 'Admin\DocumentoController@searchPending')->name('documentos.search_pendientes');
      Route::post('/documentos/send-pending', 'Admin\DocumentoController@sendPending')->name('documentos.send_pendientes');
      Route::post('/documentos/change-date/{documento_id?}', 'Admin\DocumentoController@changeDate')->name('documentos.change_date');

      Route::get('empresa/index', "Admin\EmpresaController@index")->name('empresa.index');
      Route::get('empresa/create',"Admin\EmpresaController@create")->name('empresa.create');
      Route::post('empresa/store',"Admin\EmpresaController@store")->name('empresa.store');
      Route::get('empresa/search',"Admin\EmpresaController@search")->name('empresa.search');
      Route::get('empresa/{id?}/reporte-documentos', "Admin\Reportes\ReporteMensualController@show")->name('empresas.reporte_documentos');


      /// Reporte mensual
      # Ventas mensual
      Route::post('reporte-ventas-mensual-data/{id?}', 'Admin\Reportes\ReporteMensualController@getData')->name('reportes.ventas_mensual_getdata');

      // Route::post('reporte-ventas-mensual-data/{id?}',  function($id){
        // dd($id);
        // exit();
// 
      // })->name('reportes.ventas_mensual_getdata');




      Route::get('reporte-ventas-mensual-report/{id?}', 'Admin\Reportes\ReporteMensualController@report')->name('reportes.ventas_mensual_pdf');
      Route::post('reporte-ventas-mensual-consult-date/{id?}', 'Admin\Reportes\ReporteMensualController@consultDate')->name('reportes.consult_date');

      ///


      // SUBIR CERTIFICADO
      Route::get('empresa/subir_certificado/{id}', "Admin\EmpresaController@subirCertificado")->name('empresa.subirCertificado');
      Route::post('empresa/subir_certificado/{id}', "Admin\EmpresaController@storeCertificado")->name('empresa.storeCertificado');
      Route::post('empresa/check_certificado/{id}', "Admin\EmpresaController@checkCertificado")->name('empresa.checkCertificados');
      # ------
      Route::post('empresa/update_parametros/{id}', "Admin\EmpresaController@update_parametros")->name('empresa.update_parametros');


      Route::get('empresa/{id}/informacion_defecto', "Admin\EmpresaController@saveInformacionDefecto")->name('empresa.informacion_defecto');
      Route::get('empresa/{id}/actualizarUso', "Admin\EmpresaController@updateUsos")->name('empresa.usos.update');
      Route::delete('{id_empresa}/deleteLogo/{logo?}', "Admin\EmpresaController@deleteLogo")->name('empresa.deleteLogo');

      // ACTUALIZAR
      
      // ELIMINAR
      Route::post('empresa/{id}/delete', "Admin\EmpresaController@delete")->name('empresa.delete');

      // --------------------------------Utilitarios--------------------------------
      Route::get('empresa/update-productos-precios', "Admin\EmpresaController@updatePreciosProductos")->name('empresa.update_productos_precios');
      Route::get('empresa/update-valor-venta', "Admin\EmpresaController@updateValorVenta")->name('empresa.update_valor_venta');
      Route::get('empresa/update-costos-reales', "Admin\EmpresaController@updateCostosReales")->name('empresa.update_costos_reales');
      Route::put('empresa/update-parametro/{id}', "Admin\EmpresaController@updateParametroBasic")->name('empresa.update_parametros_basic');
      Route::post('empresa/generate-plantilla/{plantilla_id}', "PDFPlantillaController@generate")->name('empresa.generate_plantilla_pdf');

      Route::get('empresa/{id?}/edit', "Admin\EmpresaController@edit")->name('empresa.edit_basic');      
      Route::post('empresa/{id?}/update', "Admin\EmpresaController@updateDataBasic")->name('empresa.update_basic');
      Route::post('empresa/{id?}/update-basic-escritorio', "Admin\EmpresaController@updateDataBasicEscritorio")->name('empresa.update_basic_escritorio');
      
      Route::post('empresa/{id?}/update-sunat', "Admin\EmpresaController@updateSunat")->name('empresa.update_sunat');
      Route::post('empresa/{id?}/update-visual', "Admin\EmpresaController@updateVisual")->name('empresa.update_visual');
      Route::post('empresa/{id?}/update-cert', "Admin\EmpresaController@storeCertificado")->name('empresa.update_certs');
      Route::post('empresa/{id?}/update-modulos', "Admin\EmpresaController@update_modulos")->name('empresa.update_modulos');
      
      Route::get('empresa/{id?}/change-estatus', "Admin\EmpresaController@changeStatus")->name('empresa.change-status');


      Route::get('empresa/{id?}/change-aplicacion-igv', "Admin\EmpresaController@changeAplicacionIGV")->name('empresa.change-aplicacion-igv');

      Route::post('empresa/{id?}/update-credenciales-tienda', "Admin\EmpresaController@storeCredencialesTienda")->name('empresa.update_credenciales_tienda');


      // Route::post('empresa/{id?}/update-visual', "Admin\EmpresaController@update_certs")->name('empresa.update_certs');

      

      //
      Route::post('empresa-update/{id?}/reset-data', "Admin\EmpresaController@resetData")->name('empresa.reset_data');
      Route::post('empresa-update/{id?}/delete-data', "Admin\EmpresaController@deleteData")->name('empresa.delete_data');
      Route::delete('empresa/{id?}/delete-logo/{logo_id?}', "Admin\EmpresaController@deleteLogo")->name('empresa.delete_logo');
      Route::get('empresa/{id?}/logo-footer-sainfo', "Admin\EmpresaController@logoFooterDefault")->name('empresa.logo_footer_sainfo');

      
      # ------------------------------------------ Ordenes ------------------------------------------
      Route::get('ordenes/index', 'Admin\OrdenPagoController@index')->name('suscripcion.ordenes.index');
      Route::get('ordenes/search', 'Admin\OrdenPagoController@search')->name('suscripcion.ordenes.search');
      Route::get('ordenes/{orden_id}/show', 'Admin\OrdenPagoController@show')->name('suscripcion.ordenes.show');
      Route::get('ordenes/{orden_id}/activar', 'Admin\OrdenPagoController@activar')->name('suscripcion.ordenes.activar');
      Route::get('ordenes/{orden_id}/pdf', 'Suscripcion\OrdenPagoController@pdf')->name('suscripcion.ordenes.pdf');

      # -------------------- Planes ---------------------

      Route::get('planes', 'Admin\PlanController@index')->name('plan.index');
      Route::get('planes-search', 'Admin\PlanController@search')->name('plan.search');
      Route::post('planes/{id?}', 'Admin\PlanController@edit')->name('plan.edit');
      Route::post('planes/{id?}/update', 'Admin\PlanController@update')->name('plan.update');


      # --------------- Plan Caracteristica ----------------------------

      Route::post('planes-caracteristicas/{id?}/update', 'Admin\PlanCaracteristicaController@update')->name('plan-caracteristica.update');
      Route::post('planes-caracteristicas/{id?}/create', 'Admin\PlanCaracteristicaController@create')->name('plan-caracteristica.create');
      Route::post('planes-caracteristicas/{id?}/delete', 'Admin\PlanCaracteristicaController@destroy')->name('plan-caracteristica.destroy');

      # ------------------------ Suscripcion --------------------------
      Route::get('suscripcion/{suscripcion_id}', 'Admin\SuscripcionController@show')->name('suscripcion.show');
      Route::post('suscripcion/{suscripcion_id}/updateDate', 'Admin\SuscripcionController@updateDate')->name('suscripcion.update_fecha');

      # ----------------------- Guias -----------------------------
      Route::get('/guias', 'Admin\GuiaController@index')->name('guias.index');
      Route::get('/guias/pending', 'Admin\GuiaController@pending')->name('guias.pending');
      Route::get('/guias/search', 'Admin\GuiaController@search')->name('guias.search');
      Route::get('/guias/search-pending', 'Admin\GuiaController@searchPending')->name('guias.search_pendientes');
      Route::post('/guias/send-pending', 'Admin\GuiaController@sendPending')->name('guias.send_pendientes');
      Route::post('/guias/delete-pdf/{id?}/{create?}', 'Admin\GuiaController@deletePdf')->name('guias.delete_pdf');


      # ----------------------- TipoPago -----------------------------
      Route::get('/tipo_pago', 'Admin\TipoPagoController@index')->name('tipo_pago.index');
      Route::get('/tipo_pago/create', 'Admin\TipoPagoController@create')->name('tipo_pago.create');
      Route::post('/tipo_pago/store', 'Admin\TipoPagoController@store')->name('tipo_pago.store');      
      Route::get('/tipo_pago/{id?}/edit', 'Admin\TipoPagoController@edit')->name('tipo_pago.edit');
      Route::post('/tipo_pago/{id?}/update', 'Admin\TipoPagoController@update')->name('tipo_pago.update');
      
      # Resumenes
      Route::get('/resumenes', 'Admin\ResumenController@index')->name('resumenes.index');
      Route::get('/resumenes/pending', 'Admin\ResumenController@pending')->name('resumenes.pending');
      Route::get('/resumenes/search', 'Admin\ResumenController@search')->name('resumenes.search');
      Route::get('/resumenes/search-pending', 'Admin\ResumenController@searchPending')->name('resumenes.search_pendientes');
      Route::post('/resumenes/send-pending', 'Admin\ResumenController@sendPending')->name('resumenes.send_pendientes');
      Route::post('/resumenes/validar/{numoper}/{docnume}', 'Admin\ResumenController@validar')->name('resumenes.validar');

      # Actions
      Route::post('actions/change_empresa', "Admin\ActionController@changeEmpresa")->name('actions.change_empresa');
      Route::post('/actions/updateDocumentsPendientes', 'Admin\ActionController@updateDocumentsPendientes')->name('actions.update_ventas_acciones');
      Route::post('/actions/updateGuiasPendientes', 'Admin\ActionController@updateGuiasPendientes')->name('actions.update_guias_acciones');
      Route::post('/actions/updateResumenesPendientes', 'Admin\ActionController@updateResumenesPendientes')->name('actions.update_resumenes_acciones');
      Route::post('/actions/updateAccionesPendientes', 'Admin\ActionController@updateAccionesPendientes')->name('actions.update_all_acciones');
      Route::post('/actions/showOpciones', 'Admin\ActionController@showOpciones')->name('show_opciones');
      Route::post('/actions/enviarDocPendientes', 'Admin\ActionController@enviarDocPendientes')->name('actions.enviar_doc_pendientes');

      # Consultar Locales de la empresa
      Route::post('/consult-locals', 'Admin\EmpresaLocalController@consultLocals')->name('empresa.consult-locals');

      # Configuración
      Route::get('config','Admin\ConfigController@index')->name('config.index');
      Route::post('config/store', 'Admin\ConfigController@store')->name('config.store');

      # Ejecutar codigo
      Route::post('config/exeCode', 'Admin\ConfigController@exeCode')->name('config.exeCode');


      # Permisos y Roles
      Route::resource('permissions', 'Admin\PermissionController');
      Route::resource('roles', 'Admin\RoleController');

      // Consultar Documentos
      Route::get('consult-documentos', 'Admin\ConsultDocController@index')->name('consultar_doc.index');
      Route::post('consult-documentos/documento', 'Admin\ConsultDocController@docConsult')->name('consultar_doc.documento');
      Route::post('consult-documentos/resumen', 'Admin\ConsultDocController@resConsult')->name('consultar_doc.resumen');

      # Usuarios
      Route::get('usuarios/mantenimiento', 'Admin\UserController@mantenimiento')->name('usuarios.index');
      Route::get('usuarios/consulta', 'Admin\UserController@search')->name('usuarios.search');
      Route::post('usuarios/store', "Admin\UserController@store")->name('usuarios.store');
      Route::post('usuarios/update', "UserController@update")->name('usuarios.update');

      Route::get('usuarios/seleccionar_empresa/{id_user?}/{id_empresa?}', "Admin\UserController@seleccionar_empresa")->name('usuarios.empresa.create');
      Route::post('usuarios/seleccionar_empresa', "Admin\UserController@store_empresa")->name('usuarios.empresa.store');
      Route::get('usuarios/show_empresa/{id?}', "Admin\UserController@empresa_show")->name('usuarios.empresa.show');
      Route::post('usuarios/toggle_active/{id}', "Admin\UserController@activeToggle")->name('usuarios.toggleActiveEstate');

      # Usuarios-Empresa      
      Route::get('usuarios-empresa/{usuario_empresa?}', 'Admin\UserEmpresaController@create')->name('usuario-empresa.create');
      Route::get('usuarios-empresa/{usuario_empresa?}/show', 'Admin\UserEmpresaController@show')->name('usuario-empresa.show');
      Route::post('usuarios-empresa', 'Admin\UserEmpresaController@store')->name('usuario-empresa.store');
      Route::post('usuarios-empresa/{id?}/delete', 'Admin\UserEmpresaController@delete')->name('usuario-empresa.delete');

      Route::post('usuarios-empresa/users', 'Admin\UserEmpresaController@searchUsers')->name('usuario-empresa.search_users');


      # Usuarios-Local
      Route::get('user-local', 'Admin\UserLocalController@index')->name('user-local.index');
      Route::get('user-local/create', 'Admin\UserLocalController@create')->name('user-local.create');
      Route::get('user-local/search', "Admin\UserLocalController@search")->name('user-local.search');
      Route::post('user-local', 'Admin\UserLocalController@store')->name('user-local.store');
      Route::post('user-local/consultLocals', 'Admin\UserLocalController@consultLocals')->name('user-local.consult_locals');
      Route::get('user-local/{usucodi}/{loccodi}/edit', 'Admin\UserLocalController@edit')->name('user-local.edit');
      Route::get('user-local/{usucodi}/{loccodi}/setDefault', 'Admin\UserLocalController@setDefaultLocal')->name('user-local.default');
      Route::put('user-local/{usucodi}/{loccodi}', 'Admin\UserLocalController@update')->name('user-local.update');
      Route::delete('user-local/{usucodi}/destroy', 'Admin\UserLocalController@destroy')->name('user-local.destroy');

      // admin . user - local . search

      # Usuarios-Documentos
      Route::get('usuarios_documentos/mantenimiento', "Admin\UserDocumentoController@index" )->name('usuarios_documentos.mantenimiento');
      Route::get('usuarios_documentos/search', "Admin\UserDocumentoController@search")->name('usuarios_documentos.search');
      Route::get('usuarios_documentos/create/{id_empresa?}/{id_user?}', "Admin\UserDocumentoController@create" )->name('usuarios_documentos.create');
      Route::post('usuarios_documentos/store', "Admin\UserDocumentoController@store" )->name('usuarios_documentos.store');
      Route::get('usuarios_documentos/edit/{id}', "Admin\UserDocumentoController@edit" )->name('usuarios_documentos.edit');  
      Route::put('usuarios_documentos/update/{id}',"Admin\UserDocumentoController@update")->name('usuarios_documentos.update');  
      Route::post('usuarios_documentos/delete/{id}',"Admin\UserDocumentoController@delete")->name('usuarios_documentos.delete');  

      # Roles
      Route::get('usuarios/assign_role/{id_user}',  "Admin\UserRoleController@create")->name('usuarios.assign_role');
      Route::post('usuarios/assign_role/{id_user}', "Admin\UserRoleController@store")->name('usuarios.assign_role_store');
                  
      # Usuario Permisos
      Route::get('usuarios-permisos/{id?}/edit', 'Admin\UserPermissionController@edit')->name('usuario-permisos.edit');
      Route::post('usuarios-permisos/{id?}', 'Admin\UserPermissionController@update')->name('usuario-permisos.update');


      ############################################## Pagina ##############################################

      # Clientes
      Route::get('pagina/clientes', 'Admin\Landing\ClienteController@index')->name('pagina.clientes.index');
      Route::post('pagina/clientes', 'Admin\Landing\ClienteController@store')->name('pagina.clientes.store');
      Route::post('pagina/clientes/search', 'Admin\Landing\ClienteController@search')->name('pagina.clientes.search');
      Route::post('pagina/clientes/{id?}/edit', 'Admin\Landing\ClienteController@update')->name('pagina.clientes.update');
      Route::post('pagina/clientes/{id?}/destroy', 'Admin\Landing\ClienteController@delete')->name('pagina.clientes.destroy');

      # Testimonios
      Route::get('pagina/testimonios', 'Admin\Landing\TestimonioController@index')->name('pagina.testimonios.index');
      Route::post('pagina/testimonios', 'Admin\Landing\TestimonioController@store')->name('pagina.testimonios.store');
      Route::post('pagina/testimonios/search', 'Admin\Landing\TestimonioController@search')->name('pagina.testimonios.search');
      Route::post('pagina/testimonios/{id?}/edit', 'Admin\Landing\TestimonioController@update')->name('pagina.testimonios.update');
      Route::post('pagina/testimonios/{id?}/destroy', 'Admin\Landing\TestimonioController@delete')->name('pagina.testimonios.destroy');

      # Testimonios
      Route::get('pagina/banners', 'Admin\Landing\BannerController@index')->name('pagina.banners.index');
      Route::get('pagina/banners/create', 'Admin\Landing\BannerController@create')->name('pagina.banners.create');
      Route::post('pagina/banners', 'Admin\Landing\BannerController@store')->name('pagina.banners.store');
      
      Route::post('pagina/banners/search', 'Admin\Landing\BannerController@search')->name('pagina.banners.search');
      Route::get('pagina/banners/{id?}/edit', 'Admin\Landing\BannerController@edit')->name('pagina.banners.edit');
      Route::post('pagina/banners/{id?}/edit', 'Admin\Landing\BannerController@update')->name('pagina.banners.update');
      Route::post('pagina/banners/{id?}/destroy', 'Admin\Landing\BannerController@delete')->name('pagina.banners.destroy');

      # Pagina Sliders
      Route::get('pagina/contabilidad-bondades', 'Admin\Landing\ContabilidadCaractController@index')->name('pagina.contabilidad-caracteristica.index');
      Route::post('pagina/contabilidad-caracteristica', 'Admin\Landing\ContabilidadCaractController@store')->name('pagina.contabilidad-caracteristica.store');
      Route::post('pagina/contabilidad-caracteristica/search', 'Admin\Landing\ContabilidadCaractController@search')->name('pagina.contabilidad-caracteristica.search');
      Route::post('pagina/contabilidad-caracteristica/{id?}/edit', 'Admin\Landing\ContabilidadCaractController@update')->name('pagina.contabilidad-caracteristica.update');
      Route::post('pagina/contabilidad-caracteristica/{id?}/destroy', 'Admin\Landing\ContabilidadCaractController@delete')->name('pagina.contabilidad-caracteristica.destroy');


      # Contabilidad -testimonios

      Route::get('pagina/contabilidad-test', 'Admin\Landing\ContabilidadTestiController@index')->name('pagina.contabilidad-testi.index');
      Route::get('pagina/contabilidad-test-create', 'Admin\Landing\ContabilidadTestiController@create')->name('pagina.contabilidad-testi.create');
      Route::post('pagina/contabilidad-test', 'Admin\Landing\ContabilidadTestiController@store')->name('pagina.contabilidad-testi.store');
      Route::post('pagina/contabilidad-test/{id?}/update', 'Admin\Landing\ContabilidadTestiController@update')->name('pagina.contabilidad-testi.update');
      Route::get('pagina/contabilidad-test/{id?}/edit', 'Admin\Landing\ContabilidadTestiController@edit')->name('pagina.contabilidad-testi.edit');
      Route::post('pagina/contabilidad-test/{id?}/destroy', 'Admin\Landing\ContabilidadTestiController@delete')->name('pagina.contabilidad-testi.destroy');

      # Notificaciones
      Route::post('notificaciones/read-all', 'Admin\NotificacionController@readMassive')->name('notificaciones.read_massive');
      Route::post('notificaciones/unread-all', 'Admin\NotificacionController@unreadMassive')->name('notificaciones.unread_massive');      
      Route::post('notificaciones/delete-all', 'Admin\NotificacionController@deleteMassive')->name('notificaciones.delete_massive');
      
      Route::get('notificaciones/{id}/read', 'Admin\NotificacionController@makeRead')->name('notificaciones.read');
      Route::get('notificaciones/{id}/unread', 'Admin\NotificacionController@makeUnRead')->name('notificaciones.unread');
      Route::get('notificaciones/{id}/delete', 'Admin\NotificacionController@destroy')->name('notificaciones.delete');      

      Route::resource('notificaciones','Admin\NotificacionController');
      
    });
  });
});