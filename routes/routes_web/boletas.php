<?php	

use Illuminate\Support\Facades\Route;

Route::prefix('boletas')->group(function(){

	Route::name('boletas.')->group(function(){

		# Listado de resumenes
		Route::get('resumen_dia','BoletasController@resumen_dia')->name('resumen_dia');

		# Listado de resumenes
		Route::get('resource/{id_resumen}/{docnume?}/{tipo?}','BoletasController@resource')->name('resource');

		# Vista para procesar las boletas
		Route::get('procesar_resumen/{numoper?}/{docnume?}/{empcodi?}','BoletasController@agregar_boleta')->name('agregar_boleta');

		# Vista para procesar las boletas
		Route::get('validar_resumen/{numoper?}/{docnume?}/{empcodi?}','BoletasController@validarResumen')->name('validar_resumen');

		# Actualizar resumen
		Route::post('update/{numoper}/{docnume}', 'BoletasController@update')->name('update');

		# Generara resumen
		Route::get('generar_resumen/{mescodi?}','BoletasController@generar_resumen')->name('generar_resumen');

		// Boletas del dia
		Route::post('boletas_dia','BoletasController@agregar_boletas')->name('boletas_dia');

		# Poner en espera
		Route::post('guardar_boleta','BoletasController@guardar_boletas')->name('guardar_boletas');

		# Enviar resumen a la sunat
		Route::post('enviar_resumen','SunatController@enviar_resumen')->name('enviar_resumen');

		# Anular boleta
		Route::post('anular_boleta','BoletasController@anular_boleta')->name('anular');

		# ticket
		Route::post('enviar_ticket','SunatController@enviar_ticket')->name('enviar_ticket');

		# 
		Route::post('eliminar','Resumen\ResumenController@destroy')->name('eliminar_resumen');

	});
	
});