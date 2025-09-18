<?php

use App\GuiaSalidaItem;
use App\Mes;
use App\Producto;
use App\Venta;
use App\XmlHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::prefix('test')->group(function () {

  Route::name('test.')->group(function () {

    Route::get('notificaciones', 'TestsController@notify');



    Route::post('printDirect/{id?}', 'TestsController@imprimirDirecto')->name('imprimir_directo');

    Route::get('testPrint', 'TestsController@testPrint')->name('test_print');

    Route::get('xmlToVenta/{empresaId}/{desdeSerie?}/{tipoDocImport?}', 'TestsController@xmlToVenta');
    Route::get('xmlResumenToVenta', 'TestsController@xmlResumenToVenta');
    Route::get('xmlResumenToResumen', 'TestsController@xmlResumenToResumen');
    Route::get('validateResumen', 'TestsController@validateResumen');

    Route::get('updateClientes', 'TestsController@updateClientes');
    Route::get('updateBoletas/{vtaOper?}', 'TestsController@updateBoletas');

    Route::get('consultStatus/{tidcodi?}/{serie?}/{num_init?}', 'TestsController@consultStatus');

    Route::get('ck5', 'TestsController@editor');

    Route::get('telefono/{tlf?}/{proveedor?}', 'TestsController@messageTlf');

    Route::get('sunat', 'TestsController@sunatRequest');

    Route::get('m', 'TestsController@m');

    Route::get('stock/{procodi?}', function ($procodi) {


      $aja = GuiaSalidaItem::updateStock2($procodi);
      // $producto = Producto::findByProCodi(($procodi));

      dd( $aja );


    });


    Route::get('phpinfo/', function () {

      $meses = Mes::all();
      foreach ($meses as $mes) {
        $mes->toggleCierre();
      }

      // $fh = FileHelper();
      // $path = 'documentos\XMLData';
      // return \Storage::disk('s3')->get($path);
      // if (!auth()->user()->isAdmin()) {
      //   return back();
      // }
      phpinfo();
    });

    Route::get('ticketera/', 'TestsController@ticketera');
    Route::get('ticketera-firma-public/', 'TestsController@firmaDigitalPublic')->name('firma_publica');
    Route::get('ticketera-firma-private/', 'TestsController@firmaDigitalImpresion')->name('firma_privada');

    // <a href="#" data-href_public="{{ route('tests.forma_publica') }}" data-href_privada="{{  route('tests.forma_privada') }}">
    // FIRMAAA
    // </a>




    Route::get('tasks/{taks}', 'TestsController@tasksExecute');

    Route::get('comprobar_xmls/{mescodi?}/{tidcodi?}',  function ($mescodi, $tidcodi = "03") {


      if (!auth()->user()->isAdmin()) {
        return back();
      }

      $docs = Venta::where('Mescodi', $mescodi)
        ->where('TidCodi', $tidcodi)
        ->get();

      $empresa = get_empresa();
      $fileHelper = fileHelper($empresa->ruc());
      $resultado = [];

      Log::info("--------------------- INICIO ESCANEO {$docs->count()} ---------------------");

      $index = 1;
      foreach ($docs as $venta) {
        $numeracion = $venta->VtaNume;
        $envioXML = $venta->nameFile('.xml');
        if ($fileHelper->xmlExist($envioXML)) {
          $content = $fileHelper->getEnvio($venta->nameFile('.xml'));
          $path = $fileHelper->saveTemp($content, $venta->nameFile('.xml'));
          $info = XmlHelper::extract_value(['cbc:PayableAmount'], $path, $envioXML, false);
          $totalSunat = $info[0];
          if ($venta->VtaImpo == $totalSunat) {
            Log::info("Index {$index} - {$venta->VtaOper} {$numeracion} ({$venta->VtaFvta}) SI-COINCIDE EL MONTO Del Documento ({$venta->VtaImpo}) al consignado a la Sunat: ({$totalSunat})");
          } else {
            Log::info("Index {$index} - {$venta->VtaOper} {$numeracion} ({$venta->VtaFvta}) NO-COINCIDE EL MONTO Del Documento ({$venta->VtaImpo}) al consignado a la Sunat: ({$totalSunat})");
          }
        } else {
          Log::info("Index {$index} - {$venta->VtaOper} {$numeracion} ({$venta->VtaFvta}) NO-SE-ENCONTROL el xml ({$envioXML})");
        }

        $index++;
      }

      Log::info("--------------------- FIN ESCANEO ---------------------");

      return $resultado;
    });


    Route::get('reorden/{fecha?}/{tidcodi?}',  function ($fecha, $tidcodi = "03") {

      if (!auth()->user()->isAdmin()) {
        return back();
      }
      $docs = Venta::where('VtaFvta', $fecha)
        ->where('TidCodi', $tidcodi)
        ->get();

      $numero_actual = null;



      foreach ($docs as $doc) {

        if (is_null($numero_actual)) {
          $numero_actual = (int) $doc->VtaNumee;
          continue;
        }

        $numero_actual++;


        $vtaNume = agregar_ceros($numero_actual, 6, 0);

        $doc->update([
          'VtaNumee' =>   $vtaNume,
          'VtaNume' => $doc->VtaSeri . '-' . $vtaNume,
        ]);
      }
      notificacion('Reordenacion lista', '', 'success');
      return back();
    });


    // blade
    Route::get('blade', 'TestsController@blade');

    // pdf
    Route::get('pdf', 'TestsController@pdf');

    Route::get('tasa_cambio', 'TestsController@tasa_cambio');

    // blade
    Route::get('verificar_documentos', 'TestsController@verificar_documentos');

    // blade
    Route::post('verificar_documentos', 'TestsController@verificar_process')
      ->name('verificar_documentos')
      ->middleware('isAdmin');



    // blade
    Route::get('buscar_documento/{documento}/{tipo?}', 'TestsController@searchDocumento');


    Route::post('verificar_ticket', 'TestsController@verificar_ticket')->name('verificar_ticket');

    # envio factura
    Route::get('events/{dni?}', 'TestsController@events');

    # envio factura
    Route::get('envio_factura/{id_factura}', 'TestsController@envio_factura');

    # envio factura
    Route::get('anular/{id_factura}', 'SunatController@envio_factura');

    # envio de resumen
    Route::get('envio_resumen/{id_resumen}', 'TestsController@envio_resumen');

    # validar ticket
    Route::get('validar_ticket/{ticket}', 'TestsController@validar_ticket');

    # validar ticket
    Route::get('guardar_pdf/{id_venta}', 'TestsController@guardar_pdf');

    # sistema de permisos
    Route::get('permisos', 'TestsController@permisos');

    # Acción de anular 
    Route::get('anulacion/{id_venta?}', 'TestsController@anulacion');

    # Calcular totales
    Route::get('calcular/{id_venta?}', 'TestsController@calcular');

    # Calcular totales
    Route::get('guia/{id_guia?}', 'TestsController@guia');

    # Envio email
    Route::get('envio_email', 'MailsController@envio_email')->middleware('isAdmin');

    # Envio email
    Route::get('random/{param1?}/{param2?}/{param3?}', 'TestsController@random')->middleware('isAdmin');

    # Envio email
    Route::get('js', 'TestsController@js')->middleware('isAdmin');

    Route::get('cd/{dni?}', 'TestsController@consultDocument')->middleware('isAdmin');
  });
});

// });