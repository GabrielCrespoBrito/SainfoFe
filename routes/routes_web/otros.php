<?php

Route::post('condicion/guardar_default', 'CondicionVentasController@saveDefault')->name('condicion.guardar_default');

Route::post('mail/enviar_documento', 'MailsController@enviar_documento')->name('mail.enviar_documento');

Route::post('mail/enviar_redactado', 'MailsController@enviar_radactado')->name('mail.redactado');

Route::post('mail/cotizacion_redactada', 'MailsController@cotizacion_redactada')->name('mail.cotizacion_redactada');

Route::post('mail/mails_enviados', 'MailsController@mails_enviados')->name('mail.enviados');

// Route::post('sunat/anular_documento-custom', 'SunatController@anular_documento')->name('sunat.anular_documento');

Route::get('borrar_todo/{producto?}/{usuario?}', function ($producto = false, $usuario = false) {
	// dd(func_get_args());
	if (get_setting('is_online') || get_empresa()->produccion()) {
		noficaciones("Inhabilitado el borrado masivo", "", "danger");
		return redirect()->back();
	}

	// borrar ventas
	DB::table('ventas_cab')->truncate();
	DB::table('ventas_detalle')->truncate();
	DB::table('ventas_pago')->truncate();


	// borrar ventas
	DB::table('compras_cab')->truncate();
	DB::table('compras_detalle')->truncate();
	DB::table('compras_pago')->truncate();


	// borrar venta detalle
	DB::table('ventas_ra_cab')->truncate();
	DB::table('ventas_ra_detalle')->truncate();

	// borrar cotizaciones
	DB::table('cotizaciones')->truncate();
	DB::table('cotizaciones_detalle')->truncate();

	// borrar cotizaciones detalle
	DB::table('guias_cab')->truncate();
	DB::table('guia_detalle')->truncate();

	if ($usuario == "1") {
		DB::table('usuario_documento')->truncate();
	}

	DB::table('caja_detalle')->truncate();
	DB::table('caja')->truncate();

	if ($producto == "1") {
		DB::table('productos')->truncate();
		DB::table('unidad')->truncate();
	}

	// notificaciones    
	notificacion("Borrado exitoso", "Se ha borrado todo", "success");
	return redirect()->route('home');
})->middleware('permission:administracion');

Route::get('/vc',  function () {
	return active_route('asdasd');
	return get_empresa();
	return hay_internet();
});

Route::get('/t1',  function () {
	$venta = App\Venta::find("000001");
	$input = new App\XmlCreator($venta, true);
	$input->guardar();
});

Route::get('/t2',  function () {
	$venta = App\Venta::find("000059");
	$path = 'C:\xampp2\htdocs\sainfo\storage\app\XMLCDR\R-10323013760-07-F001-000015.zip';
	$name = "R-10323013760-07-F001-000015.XML";
	$rpta = App\XmlCreator::extract_value(['ResponseCode', 'Description'], $path, $name, true);
	dd("rpta", $rpta);
});


Route::get('/pdf/{html?}',  function ($html = 1) {

	$dompdf = new Dompdf\Dompdf();
	$data = App\Venta::find("000013")->dataPdf(true);
	if ($html === 1) {
		return view('ventas.pdf', $data);
	}
	$dompdf->setPaper('A4');
	$dompdf->loadHtml(view('ventas.pdf', $data));
	$dompdf->render();
	$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
	exit(0);
});

Route::get('/mailable', function () {
	$venta = App\Venta::find("000173");
	$data = [
		'to' => 'gabrielc1990poker@gmail.com',
		'subject' => 'DOCUMENTO TRIBUTARIO ELECTRONICO',
		'cliente_nombre' => $venta->cliente->PCNomb,
		'cliente_ruc' => $venta->cliente->PCRucc,
		'cliente_documento' => $venta->VtaNume,
		'attach' => [ 'pdf' => $venta->pdfPath(), ]
	];
	return (new App\Mail\EnviarDocumento($data))->render();
});



Route::get('prueba_ruc/{ruc?}', function ($ruc) {
	return consultar_ruc($ruc);
});
