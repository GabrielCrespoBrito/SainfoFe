<?php	

use Illuminate\Support\Facades\Route;

Route::prefix('unidad')->group(function(){
	Route::name('unidad.')->group(function(){

    Route::get('search', 'UnidadController@search')->name('search');
    Route::get('/{producto_id?}', 'UnidadController@index')->name('index');
    Route::post('update-tc/', 'UnidadController@updatePreciosByTipoCambio')->name('update_tc');
    Route::post('massvie-update-manual/', 'UnidadController@updateMasiveManual')->name('actualizacion_masiva_manual');
    Route::post('massvie-update/', 'UnidadController@updateMasive')->name('actualizacion_masiva');

    # Menudeo
    // Route::get('producto/{id_producto?}', 'UnidadController@mantenimiento')->name('mantenimiento');
    Route::post('filtros/', 'UnidadController@getFiltros')->name('filtros');
    
		Route::get('edit/{id_unidad?}', 'UnidadController@edit')->name('edit');
		Route::post('store/{id_producto?}', 'UnidadController@store')->name('store');
		Route::post('updatePrices/{id_producto?}', 'UnidadController@updatePrices')->name('updatePrices');
		Route::post('update/{id_producto?}', 'UnidadController@update')->name('update');    
		Route::delete('delete/{id_unidad?}', 'UnidadController@delete')->name('delete');

	});
});