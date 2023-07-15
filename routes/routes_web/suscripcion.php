<?php

Route::get('suscripcion/info','Suscripcion\SuscripcionController@show')->name('suscripcion.suscripcion.info');

Route::middleware('orden_pago.pendiente')->group(function () {
  Route::get('planes/index', 'Suscripcion\PlanController@index')->name('suscripcion.planes.index');
  Route::get('planes/{plan_id}/confirm', 'Suscripcion\PlanController@confirm' )->name('suscripcion.planes.confirm');
  Route::get('orden/{planduracion_id}/create', 'Suscripcion\OrdenPagoController@store' )->name('suscripcion.ordenes.store');
});

//
Route::get('ordenes/', 'Suscripcion\OrdenPagoController@index')->name('suscripcion.ordenes.index');
Route::get('admin/ordenes/', 'Suscripcion\OrdenPagoController@indexAdmin')->name('suscripcion.ordenes.index.admin');
Route::get('ordenes/{orden_id}/pdf', 'Suscripcion\OrdenPagoController@pdf')->name('suscripcion.ordenes.pdf');
Route::get( 'ordenes/{orden_id}', 'Suscripcion\OrdenPagoController@show')->name('suscripcion.ordenes.show');
Route::get('admin/ordenes/{orden_id}', 'Suscripcion\OrdenPagoController@showAdmin')->name('suscripcion.ordenes.show.admin');

// Route::get('admin/ordenes/{orden_id}/activar', 'Suscripcion\OrdenPagoController@activar')->name('suscripcion.ordenes.activar');