<?php

use Illuminate\Support\Facades\Route;

Route::middleware('tenant.exists')->group(function () {

  Route::prefix('productos')->group(function () {

    Route::name('productos.')->group(function () {

      // productos/
      Route::get('/', 'ProductosController@index')->name('index');

      # Menudeo
      Route::get('/{id_producto?}/menudeo', 'UnidadController@mantenimiento')->name('unidad.mantenimiento');

      // productos/search
      Route::get('search', 'ProductosController@search')->name('consulta');

      Route::get('actualizar-ultimo-costo', 'ProductosController@updateUltimoCosto')->name('ultimo_costo');

      // productos/search
      Route::get('search_select2', 'ProductosController@search_select2')->name('buscar_select2');

      // productos/buscar_grupo
      Route::post('buscar_grupo', 'GruposController@buscar_grupo')->name('buscar_grupo');

      // Actualizar almacen
      Route::post('update-almacen/{id}', 'ProductosController@updateAlmacen')->name('update_almacen');

      // productos.update_almacen


      // ajaxs : productos/consultar_noperacion
      Route::post('consultar_noperacion', 'ProductosController@consultar_noperacion')->name('consultar_noperacion');

      // ajaxs : productos/consultar_codigo
      Route::post('consultar/codigo', 'ProductosController@consultar_codigo')->name('consultar_codigo');

      // ajaxs : productos/consultar_codigo		
      Route::post('consultar/datos', 'ProductosController@consultar_datos')->name('consultar_datos');

      // ajaxs : productos/consultar_codigo		
      Route::post('consultar/alldatos', 'ProductosController@consultar_alldatos')->name('consultar_alldatos');
      // productos/store
      Route::post('store', 'ProductosController@store')->name('store');

      // productos/update
      Route::post('update', 'ProductosController@update')->name('update');

      Route::post('eliminar', 'ProductosController@eliminar')->name('eliminar');

      Route::post('restaurar', 'ProductosController@restaurar')->name('restaurar');

      // productos/update
      Route::post('vendidos', 'ProductosController@search_productos_vendidos')->name('vendidos');

      // -		
      Route::get('importData', 'ProductosController@import_data')->name('import_data');
      Route::post('importStore', 'ProductosController@import_store')->name('import_store');

      // productos/search
      Route::get('excel-ejemplo-importacion/{tipo?}', 'ProductosController@excelEjemplo')->name('excel_ejemplo');
      Route::get('excel-products-template', 'ProductosController@downloadProduct')->name('excel_products_template');


      // productos/search
      Route::post('update-stock', 'ProductosController@updateStock')->name('update_stock');

      Route::get('update-inventarios', 'ProductosController@updateInventarios')->name('update_inventarios');
    });
  });
});
