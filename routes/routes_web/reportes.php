<?php

use Illuminate\Support\Facades\Route;

Route::prefix('reportes')->group(function () {

  // Reporte Compra Venta
  Route::get('compra_venta', 'Reportes\ReporteCompraVentaController@create')->name('reportes.compra_venta');
  Route::post('buscar_producto', 'Reportes\ReporteCompraVentaController@search')->name('reportes.buscar_producto');


  Route::get('ventas/{venta?}', 'ReportesController@ventas')->name('reportes.ventas');

  Route::get('compras/', 'Compra\CompraController@showReporte')->name('reportes.compras');
  Route::post('compras', 'Compra\CompraController@pdfReporte')->name('reportes.compras_pdf');

  Route::post('importe_mensual', 'ReportesController@importe_mensual')->name('reportes.importe_mensual');

  Route::post('documentos_faltantes', 'ReportesController@documentos_faltantes')
    ->name('reportes.documentos_faltantes');

  Route::post('pdf_documentos_faltantes', 'ReportesController@pdf_documentos_faltantes')->name('reportes.pdf_documentos_faltantes');

  Route::post('buscar_rangos', 'ReportesController@buscar_rangos')->name('reportes.buscar_rangos');

  Route::get('visualizacion/{tipo_reporte?}/{cliente?}/{local?}/{fecha_desde?}/{fecha_hasta?}/{tipo_documento?}/{serie?}/{vendedor?}/{tipo_elemento?}', 'ReportesController@visualizacion')->name('reportes.visualizacion');

  Route::get('consultar_documentos', 'ReportesController@consultar_documentos')->name('reportes.consultar_documentos');

  Route::get('kardex_fisico', 'ReportesController@kardex_fisico')->name('reportes.kardex_fisico');  

  # Kardex Valorizado
  Route::get('kardex_valorizado', 'ReportesController@kardex_valorizado')->name('reportes.kardex_valorizado');
  Route::get('kardex_valorizado_pdf/{mes}/{local}/{tipo}/{reprocesar?}/{formato?}', 'ReportesController@kardex_valorizado_pdf')->name('reportes.kardex_valorizado_pdf');

  # Kardex Movimientos entre almacenes
  Route::get('kardex-movimientos', 'Reportes\KardexTrasladoController@create')->name('reportes.kardex_traslado');
  Route::post('kardex-movimientos', 'Reportes\KardexTrasladoController@report')->name('reportes.kardex_traslado_report');

  Route::post('productos/reportes-movimientos', 'ReportesController@productoMovimientosReporte')->name('productos.reporte_movimientos');

  Route::post('kardex_pdf', 'ReportesController@kardex_pdf')->name('reportes.kardex_pdf');

  # Reporte
  Route::get('kardex_fecha', 'Reportes\KardexByDateController@create')->name('reportes.kardex_by_date');
  Route::post('kardex-fecha-report', 'Reportes\KardexByDateController@store')->name('reportes.kardex_by_date_create');

  # Productos mas vendidos
  Route::get('productos_mas_vendidos', 'Reportes\ProductoController@create')->name('reportes.productos_mas_vendidos');
  Route::post('productos_mas_vendidos/pdf/', 'Reportes\ProductoController@showPDF')->name('reportes.productos_mas_vendidos.pdf');

  # Mejores Clientes
  Route::get('mejores_clientes', 'Reportes\ClienteController@mejoresClientes')->name('reportes.mejores_clientes');
  // Route::get('mejores_clientes/pdf/{fecha_desde?}/{fecha_hasta}/{local}', 'Reportes\ClienteController@mejoresClientesPdf')->name('reportes.mejores_clientes.pdf');
  Route::get('mejores_clientes/pdf', 'Reportes\ClienteController@mejoresClientesPdf')->name('reportes.mejores_clientes.pdf');

  //
  Route::get('clientes', 'Reportes\ClienteController@deudas')->name('reportes.clientes');
  Route::get('clientes-pdf', 'Reportes\ClienteController@deudasPdf')->name('reportes.clientes_pdf');

  // -------------------------------------------------------------------------------
  Route::get('entidades', 'Reportes\EntidadReporteController@create')->name('reportes.entidad');
  Route::post('entidades', 'Reportes\EntidadReporteController@report')->name('reportes.entidad_report');
  // -------------------------------------------------------------------------------

  // Reporte facturación electronica   
  Route::get('facturacion-electronica', 'Reportes\FacturacionElectronicaController@show')->name('reportes.facturacion_electronica');
  Route::get('facturacion-electronic/search', 'Reportes\FacturacionElectronicaController@searchTable')->name('reportes.facturacion_electronica.search');
  Route::get('facturacion-electronica/generate', 'Reportes\FacturacionElectronicaController@pdf')->name('reportes.facturacion_electronica.pdf');

  
  // Reporte guias electronica   
  Route::get('guia-electronica', 'Reportes\GuiaElectronicaController@show')->name('reportes.guias');
  Route::get('guia-electronica/search', 'Reportes\GuiaElectronicaController@searchTable')->name('reportes.guias.search');
  Route::get('guia-electronica/generate', 'Reportes\GuiaElectronicaController@pdf')->name('reportes.guias.pdf');

  # Ventas Anular
  Route::get('ventas_anual', 'Reportes\VentasController@ventas_anual')->name('reportes.ventas_anual');
  // Route::get('ventas_anual/pdf/{year?}/', 'Reportes\VentasController@ventas_anual_pdf')->name('reportes.ventas_anual.pdf');
  Route::get('ventas_anual/pdf/', 'Reportes\VentasController@ventas_anual_pdf')->name('reportes.ventas_anual.pdf');
  Route::post('ventas_anual_pdf_create/{year?}/', 'Reportes\VentasController@ventas_anual_pdf_create')->name('reportes.ventas_anual_pdf_create');


  // Utilidades ventas
  Route::get('util-ventas', 'ReportesController@utilidades_ventas')->name('reportes.util-ventas');
  Route::get('util-ventas-pdf', 'ReportesController@utilidades_ventas_pdf')->name('reportes.util-ventas.pdf');

  # Ventas mensual
  Route::get('ventas-mensual', 'Reportes\VentasMensualController@show')->name('reportes.ventas_mensual');
  Route::post('ventas-mensual-data', 'Reportes\VentasMensualController@getData')->name('reportes.ventas_mensual_getdata');
  Route::get('ventas-mensual-pdf', 'Reportes\VentasMensualController@report')->name('reportes.ventas_mensual_pdf');
  Route::post('ventas-mensual-consult-date', 'Reportes\VentasMensualController@consultDate')->name('reportes.consult_date');
  
  # Ventas mensual
  Route::get('validate-documentos-mensual', 'Reportes\VentasValidateController@show')->name('reportes.validate_documentos_mensual');
  Route::get('validate-documentos-mensual-report', 'Reportes\VentasValidateController@make')->name('reportes.validate_documentos_mensual_report');

  # Compras Mensual
  Route::get('compras-mensual', 'Reportes\ComprasMensualController@create')->name('reportes.compras_mensual.create');
  Route::get('compras-mensual-pdf', 'Reportes\ComprasMensualController@pdf')->name('reportes.compras_mensual.pdf');

  # Lista de precios
  Route::get('listaPrecios', 'Reportes\ReporteListaPrecioController@create')->name('reportes.listaprecio.show');  
  Route::post('listaPrecios', 'Reportes\ReporteListaPrecioController@pdf')->name('reportes.listaprecio.pdf');

  # Lista de precios
  Route::get('detracciones', 'Reportes\DetraccionReportController@create')->name('reportes.detraccion.create');
  Route::get('detracciones-pdf', 'Reportes\DetraccionReportController@pdf')->name('reportes.detraccion.pdf');    

  # Inventario valorizado
  Route::get('inventario-valorizado', 'Reportes\InventarioValorizadoReportController@create')->name('reportes.inventario_valorizado.create');
  Route::get('inventario-valorizado/pdf', 'Reportes\InventarioValorizadoReportController@pdf')->name('reportes.inventario_valorizado.pdf');

  # Reportes de Tipos de pago
  Route::get('ventas-tipos-pago/create', 'Reportes\VentaTipoPagoReportController@create')->name('reportes.ventas_tipopago.create');
  Route::post('ventas-tipos-pago/pdf', 'Reportes\VentaTipoPagoReportController@pdf')->name('reportes.ventas_tipopago.pdf');
  Route::get('ventas-tipos-pago/pdf-caja/{caja_id?}/{tipo_pago?}', 'Reportes\VentaTipoPagoReportController@pdfByCaja')->name('reportes.ventas_tipopago.pdf_caja');


  # Reportes de vendedor-ventas
  Route::get('vendedor-ventas', 'Reportes\VendedorVentaReportController@create')->name('reportes.vendedor_venta.create');
  Route::post('vendedor-ventas/report', 'Reportes\VendedorVentaReportController@report')->name('reportes.vendedor_venta.report');

  # Reportes de vendedor-producto
  Route::get('vendedor-producto', 'Reportes\VendedorProductoReportController@create')->name('reportes.vendedor_producto.create');
  Route::post('vendedor-producto/report', 'Reportes\VendedorProductoReportController@report')->name('reportes.vendedor_producto.report');

  # Reportes de vendedor-producto
  Route::get('productos-stock', 'Reportes\ProductoStockReportController@create')->name('reportes.productos_stock.create');
  Route::post('productos-stock/report', 'Reportes\ProductoStockReportController@report')->name('reportes.productos_stock.report');


  # Reportes de vendedor-estadisticas
  Route::get('vendedor-estadisticas','Reportes\VendedorEstadisticaReportController@create')->name('reportes.vendedor_estadistica.create');
  Route::post('vendedor-estadisticas/report', 'Reportes\VendedorEstadisticaReportController@report')->name('reportes.vendedor_estadistica.report');
  Route::post('vendedor-estadisticas/report-render', 'Reportes\VendedorEstadisticaReportController@reportRender')->name('reportes.vendedor_estadistica.report_render');

  # Reportes de vendedor-ventas-prodcutos
  Route::get('vendedor-ventas-prodcutos', 'Reportes\VendedorVentaProductoReportController@create')->name('reportes.vendedor_venta_productos.create');
  Route::post('vendedor-ventas-prodcutos/report', 'Reportes\VendedorVentaProductoReportController@report')->name('reportes.vendedor_venta_producto.report');
  Route::post('vendedor-ventas-prodcutos/report-render', 'Reportes\VendedorVentaProductoReportController@reportRender')->name('reportes.vendedor_venta_productos.report_render');


  # Reportes de Ganancias
  Route::get('utilidades', 'Reportes\UtilidadesController@create')->name('reportes.utilidades.create');
  Route::post('utilidades', 'Reportes\UtilidadesController@show')->name('reportes.utilidades.show');
  Route::get('utilidades-pdf/{fecha_desde?}/{fecha_hasta?}/{local?}/{grupo}', 'Reportes\UtilidadesController@pdfComplete')->name('reportes.utilidades.pdf_complete');
  Route::get('utilidades-pdf-fecha/{fecha?}/{local?}/{grupo?}', 'Reportes\UtilidadesController@pdfByFecha')->name('reportes.utilidades.pdf_fecha');

});