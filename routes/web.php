<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

# Pagina de inicio
include('routes_web/landing.php');
include('routes_web/test.php');
include('routes_web/ubigeo.php');

# Area Administrativa
include('routes_web/admin.php');

Auth::routes();

// Logearse en el area de administración
Route::get('login-admin', "Auth\LoginAdminController@showLoginForm")->name('admin.loginForm');

Route::post('login-admin', "Auth\LoginAdminController@login")->name('admin.login');
Route::post('logout-admin', "Auth\LoginAdminController@logout")->name('admin.logout');

Route::get('logout', function(){
  optional(auth())->logout();
  return redirect()->route('login');
});

Route::get('pdfv/{token?}/{documento?}', "Documento\ConsultComprobanteApi@consult")->name('consultaComprobante.api');

Route::get('/consult-documento/{ruc}/{isRuc?}', "Documento\DocumentoApiConsultController@consult")->name('busquedaDocumentos.api');

Route::get('/busquedaDocumentos', "ClienteAdministracion\ClienteDashboardController@busquedaDocumentos")
  ->name('busquedaDocumentos');

Route::get('admin/runcommand/{comand}', "AdminController@runComandos")->name('admin.comandos');
Route::post('admin/upload-img-banner-footer', "AdminController@saveImageBannerPDF")->name('admin.save_img_footer_banner');


include('routes_web/documentos.php');

// login cliente
Route::post('login_cliente', 'ClienteAdministracion\LoginControllerClientes@login')->name('login_cliente');
Route::post('logoutCliente', 'ClienteAdministracion\LoginControllerClientes@logout')->name('logoutCliente');

Route::group(['middleware' => ['auth', 'usuario.activo']], function () {

  Route::get('/generarPdfs/{activar}', "HomeController@generarPdfs")->name('generarPdfs');
  
  // Formulario para para enviar el telefono y validar
  Route::get('/verificar', "UsersController@verificarUser")->name('verificar')->middleware(['registration_user.active:0','usuario.verificar:0']);  
  Route::post('/storePhone', "UsersController@storePhone")->name('usuario.store_phone')->middleware(['registration_user.active', 'usuario.verificar:0']);
  Route::get('/reenviarCodigo', "UsersController@reenviarCodigo")->name('usuario.reenviar_codigo')->middleware(['registration_user.active','usuario.verificar:0']);
  Route::post('/verifyCode', "UsersController@verifyCode")->name('usuario.verificar_codigo')->middleware(['registration_user.active:1', 'usuario.verificar:0']);


  // Consultar ruc de la empresa
  Route::post('consult-ruc', "ClienteProveedor\ConsultDocumentController@consultRUC")
  ->name('consulta_ruc')
  ->middleware('cors');

  // Consultar ruc de la empresa
  Route::post('compartir-doc/{doc?}', "Documento\ConsultComprobanteApi@compartir")
  ->name('compartir-doc');


  // Verificar clave sol y guardar la información de la empresa
  Route::get('/verificarSol', "UsersController@showFormSol")->name('usuario.verificar_empresa')
  ->middleware(['registration_user.active', 'usuario.verificar:1']);

  Route::post('/verificarSolStore', "UsersController@storeSolEmpresa")->name('usuario.store_verificar_empresa')->middleware(['registration_user.active', 'usuario.verificar:1']);
  
  Route::post('/storeEmpresaInfo', "UsersController@saveEmpresaInformation")->name('usuario.store_empresa_informacion');
  
  // form para elegir empresa y periodo a trabajar
  Route::get('/redirect', "HomeController@redirectTo")->name('redirect');
  Route::get('/elegir_empresa', "HomeController@ElegirEmpresa")->name('elegir_empresa')->middleware(['usuario.empresa']);
  Route::post('/elegir_empresa', "HomeController@EmpresaSeleccionada")->name('empresa.seleccionada')->middleware('empresa.active');
  
  Route::group(['middleware' => 'elegir.periodo'], function () {
    
    Route::middleware('tenant.exists')->group(function () {

      Route::get('/home', "HomeController@index")->name('home');
    });

    Route::middleware('suscripcion.active')->group(function () {

      # Configuración de la empresa
      Route::get('empresa/config-final', "Empresa\EmpresaController@configFinal")
      ->name('empresa.config_final')
      ->middleware('verifiy.config:0');

      # Información de certificado y contraseña
      Route::post('empresa/cert-data/{empresa_id}', "Empresa\EmpresaController@certStore")
      ->name('empresa.cert.store');      
      // ->middleware('verifiy.config:0');

      Route::middleware('verifiy.config:1')->group(function () {

        # clientes 
        include('routes_web/miscellaneous.php');

        # clientes 
        include('routes_web/clientes.php');

        # Sire
        include('routes_web/sire.php');

        # usuarios  
        include('routes_web/tienda.php');
        
        # usuarios  
        // include('routes_web/permission.php');

        # usuarios  
        include('routes_web/formas_pago.php');
        include('routes_web/medios_pagos.php');
        
        # usuarios  
        include('routes_web/utilitarios.php');
        
        # usuarios  
        include('routes_web/sunat.php');

        # usuarios  
        include('routes_web/vendedores.php');
        # cierres  
        include('routes_web/cierres.php');

        # usuarios  
        include('routes_web/usuarios.php');
        
        # usuarios  
        include('routes_web/tipo_cambio.php');
        
        # contratas
        include('routes_web/contratas.php');
        
        include('routes_web/contratas_entidad.php');
        
        # usuarios  
        include('routes_web/usuarios_documentos.php');
        
        # productos 
        include('routes_web/productos.php');

        # productos 
        include('routes_web/importaciones.php');

        # contingencia
        include('routes_web/contingencia.php');
        
        # unidad 
        include('routes_web/unidad.php');
        
        # ventas
        include('routes_web/ventas.php');
        
        # boletas
        include('routes_web/boletas.php');
        
        # cotizaciones
        include('routes_web/cotizaciones.php');
        
        # compras
        include('routes_web/compras.php');
        
        # grupos
        include('routes_web/grupos.php');
        
        # marcas
        include('routes_web/marcas.php');
        
        # familias
        include('routes_web/familias.php');
        
        # cajas
        include('routes_web/cajas.php');

        # cajas
        include('routes_web/zonas.php');
        
        include('routes_web/banco.php');
        
        # guia_remision
        include('routes_web/guia.php');

        # guia_remision
        include('routes_web/toma_inventario.php');

        # produccion
        include('routes_web/produccion.php');

        # reportes
        include('routes_web/reportes.php');
  
        # lista precios
        include('routes_web/listaprecio.php');
  
        # nube
        include('routes_web/nube.php');
  
        # pagos
        include('routes_web/pagos.php');

        ///
        include('routes_web/empresa_transporte.php');
        include('routes_web/vehiculo.php');
        include('routes_web/transportista.php');

        # export
        include('routes_web/export.php');

        # loremp ipsum odlor
        include('routes_web/consolidacion.php');

        include('routes_web/resumenes.php');
      
      });

    });

      # empresa
      include('routes_web/empresa.php');

      # otros
      include('routes_web/otros.php');

      # email
      include('routes_web/mail.php');

      # usuarios_local
      include('routes_web/usuarios_local.php');


      # usuarios_local
      include('routes_web/local.php');

      # Suscripcion
      include('routes_web/suscripcion.php');
  
  }); // middleware elegir.periodo

}); // middleware auth