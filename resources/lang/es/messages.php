<?php

use App\Admin\SystemStat\SystemStat;

return [
  'exit' => 'Salir',
  'acepto_terminos' => 'Acepto los Términos y Condiciones y la Política de Privacidad de Sainfo',
  'register_user' => 'Registro de usuarios',
  // 
  SystemStat::EMPRESAS_ACTIVAS  => 'Empresas Activos',
  SystemStat::USUARIOS_ACTIVOS  => 'Usuarios Activos',
  SystemStat::VENTAS_TOTALES    => 'Ventas Totales',
  SystemStat::COMPRAS_TOTALES   => 'Compras Totales',  
  // Acciones requeridas
  SystemStat::DOCUMENTOS_PENDIENTES => "Documentos Por Enviar a Sunat ",
  SystemStat::GUIAS_POR_ENVIAR => "Guias Por Enviar a Sunat",
  SystemStat::RESUMENES_POR_VALIDAR => "Resumenes Por Validar",
  SystemStat::PROCESAR_ORDENES_EMPRESA => "Ordenes de Compra de Empresa por procesar",

  // Acciones
  PermissionSeeder::A_INDEX => 'Listado',
  PermissionSeeder::A_CREATE => 'Crear',
  PermissionSeeder::A_EDIT => 'Modificar',
  PermissionSeeder::A_DELETE => 'Eliminar',
  PermissionSeeder::A_SHOW => 'Mostrar',

  // Recursos
  PermissionSeeder::R_CLIENTE => 'Clientes',
  PermissionSeeder::R_MARCA => 'Marcas',
  PermissionSeeder::R_FAMILIA => 'Familias',
  PermissionSeeder::R_PRODUCTO => 'Productos',
  PermissionSeeder::R_GRUPO => 'Grupos',
  PermissionSeeder::R_PAGO => 'Pagos',
  PermissionSeeder::R_CUENTA => 'Cuentas',
  PermissionSeeder::R_FORMAPAGO => 'Forma de Pago',
  PermissionSeeder::R_VENDEDOR => 'Vendedores',
  PermissionSeeder::R_ZONA => 'Zonas',
  PermissionSeeder::R_EMPRESATRANSPORTE => 'Empresas de Transporte',
  PermissionSeeder::R_TRANSPORTISTA => 'Transportistas',
  
  PermissionSeeder::R_VENTA => "Ventas",
  PermissionSeeder::R_CLIENTE => "Clientes",
  PermissionSeeder::R_MARCA => "Marcas",
  PermissionSeeder::R_FAMILIA => 'Familias',
  PermissionSeeder::R_PRODUCTO => 'Productos',
  PermissionSeeder::R_GRUPO => 'Grupos',
  PermissionSeeder::R_LISTAPRECIO => 'Lista Precios',
  PermissionSeeder::R_PREVENTA => 'Cotizaciones',
  PermissionSeeder::R_COMPRA => 'Compras',
  PermissionSeeder::R_ORDENCOMPRA => 'ORDEN DE COMPRA',
  
  PermissionSeeder::R_REPORTE => 'Reportes',
  PermissionSeeder::R_GUIASALIDA => 'Guias de Salida',
  PermissionSeeder::R_GUIATRANSPORTISTA => 'Guias Transportista',
  PermissionSeeder::R_GUIAINGRESO => 'Guias de Ingreso',
  PermissionSeeder::R_CAJA => 'Cajas',
  PermissionSeeder::R_BANCO => 'Bancos',
  PermissionSeeder::R_LOCAL => 'Locales',
  PermissionSeeder::A_REPORTE_MOVIMIENTO => 'Movimientos', 
  PermissionSeeder::A_UPDATESTOCK => 'Actualizar Stock', 
  PermissionSeeder::A_MENUDEO => 'Menudeo', 
  PermissionSeeder::A_SHOWPRECIOS => 'Ver Precios',
  PermissionSeeder::A_UPDATEPRECIOS => 'Actualizar Precios',
  PermissionSeeder::A_UPDATEPRECIOSMASIVE => 'Actualizar Precios Masivamente',
  PermissionSeeder::A_UPDATEPRECIOSTIPOCAMBIO => 'Actualizar Precios Por Tipo de Cambio',
  PermissionSeeder::A_IMPRIMIR => 'Imprimir',
  PermissionSeeder::A_VERCOSTOS => 'Ver Costos',
  PermissionSeeder::A_LIBERAR => 'Liberar',
  PermissionSeeder::A_ANULAR => 'Anular',
  PermissionSeeder::A_EMAIL => 'Email', // = "anular";
  PermissionSeeder::A_RESUMENES => 'Resumenes', // = "resumenes";
  PermissionSeeder::A_MODIFICAR_PRECIO => 'Modificar Precios',  
  PermissionSeeder::A_CONTINGENCIA => 'Contingencia', // = "contingencia";
  PermissionSeeder::A_CONSULTARDOCUMENTO => 'Consultar Documentos', // = "consultar_documentos";
  PermissionSeeder::A_DESPACHO => 'Despacho', // = "despacho";
  PermissionSeeder::CAJA_VER_MONTOS => 'Ver Montos',
  'produccion-manual' => 'Producción Manual',

  

  // Vendedor
  PermissionSeeder::A_VENDEDORVENTAS => 'Vendedor - Ventas', // = "despacho";
  PermissionSeeder::A_VENDEDORESTADISTICA => 'Vendedor - Estadisticas', // = "despacho";
  PermissionSeeder::A_VENDEDORPRODUCTO => 'Vendedor - Productos', // = "despacho";

  PermissionSeeder::A_TRASLADO => 'Traslado', // = "traslado";
  PermissionSeeder::A_COMPRAVENTA => 'Compra Venta', // "compra-venta";
  PermissionSeeder::A_FACTURACION => 'Facturación', // "facturacion";
  PermissionSeeder::A_TIPOPAGO => 'Venta por Tipo Pago', // "venta-tipo-pago";
  PermissionSeeder::A_KARDEXFISICO => 'Kardex Fisico', // "kardex-fisico";
  PermissionSeeder::A_KARDEXVALORIZADO => 'Kardex Valorizado', // "kardex-valorizado";
  PermissionSeeder::A_KARDEXPORFECHA => 'Kardex Por fecha', // "kardex-por-fecha";
  PermissionSeeder::A_KARDEXTRASLADO => 'Kardex Traslado', // "kardex-traslado";
  PermissionSeeder::A_GUIAELECTRONICA => 'Guias Electronicas', // "guias-electronicas";
  PermissionSeeder::A_CONTABLEVENTASMENSUAL => 'Ventas Mensual', // "contable-ventas-mensual";
  PermissionSeeder::A_CONTABLECOMPRASMENSUAL => 'Compras Mensual', // "contable-compras-mensual";
  PermissionSeeder::A_VENTA => 'Ventas', // "ventas";
  PermissionSeeder::A_VER_NOTAS_VENTA => 'Ver Listado de Notas de Venta', // "ventas";
  PermissionSeeder::A_COMPRA => 'Compras', // "compras";
  PermissionSeeder::A_DETRACCION => 'Detracción', // "detraccion";
  PermissionSeeder::A_LISTAPRECIO => 'Lista de Precios', // "listaprecio";
  PermissionSeeder::A_GUIA => 'Guias', // "guia";
  PermissionSeeder::A_ENTIDAD => 'Cliente/Proveedor',
  PermissionSeeder::A_CLIENTEDEUDA => 'Deuda Cliente',
  PermissionSeeder::A_UTILIDADESVENTAS => 'Utilidades Ventas',
  PermissionSeeder::A_INVENTARIOVALORIZADO => 'Inventario Valorizado',
  PermissionSeeder::A_PRODUCTOMASVENDIDO => 'Productos mas Vendidos',
  PermissionSeeder::A_MEJORESCLIENTES => 'Mejores Clientes',
  PermissionSeeder::A_VENTASANUAL => 'Ventas Anual',
  PermissionSeeder::A_UTILIDADESVENTAS2 => 'Utilidades Ventas-2',
  PermissionSeeder::A_VENDEDORZONA => 'Vendedor Zona',
  
  PermissionSeeder::A_RECURSO => 'Recursos', 
  PermissionSeeder::A_MOVIMIENTOS => 'Movimientos', 
  PermissionSeeder::A_CREATE_MOVIMIENTOS => 'Registrar Movimientos Ingreso/Egreso',
  PermissionSeeder::A_EDIT_MOVIMIENTOS => 'Modificar Movimientos Ingreso/Egreso',
  PermissionSeeder::A_CUENTASPORPAGAR => 'Cuentas por Pagar',
  PermissionSeeder::A_CUENTASPORCOBRAR => 'Cuentas por Cobrar',
  PermissionSeeder::A_APERTURAR => 'Aperturar',
  PermissionSeeder::A_APERTURADAS => 'Aperturadas',
  PermissionSeeder::A_REAPERTURAR => 'Reaperturar',
  PermissionSeeder::A_CERRAR => 'Cuentas por Cobrar',
  PermissionSeeder::A_REPORTE_COMPRA_VENTA => 'Reportes Compra/Venta',
  PermissionSeeder::A_REPORTE => 'Reporte',
  PermissionSeeder::A_REPORTE_INGRESO_EGRESO => 'Reporte Movimientos Ingreso/Egreso',
  PermissionSeeder::A_PRODUCTOSTOCK => 'Productos Stock',
  PermissionSeeder::A_TOMAINVENTARIO => 'Toma de Inventario',
  PermissionSeeder::A_IMPORTARPRODUCTO => 'Importar Productos',
  PermissionSeeder::A_IMPORTARVENTA => 'Importar Ventas',
  PermissionSeeder::A_EXPORTAR_COMPRAVENTA => 'Exportar Compras/Ventas',
  PermissionSeeder::A_CIERRE_MES => 'Cierre de Mes',
  PermissionSeeder::A_MEDIOPAGO => 'Medios de Pagos',
  PermissionSeeder::A_PARAMETRO => "Parametros",
  PermissionSeeder::A_IMPORTARPRODUCTO => "Importar Productos",
  PermissionSeeder::A_IMPORTARVENTA => "Importar Ventas",
  PermissionSeeder::A_EXPORTAR_COMPRAVENTA => "Exportar Compra/Venta",
  PermissionSeeder::A_CIERRE_MES => "Cierre de Ventas del Mes",
  PermissionSeeder::R_MEDIOPAGO => "Medio de Pago",

  PermissionSeeder::R_EMPRESA => "Empresa",
  
  PermissionSeeder::R_UTILITARIO => "Utilitarios",
  'utilitarios' => "Utilitarios",

  //  
  'stats' => [
    "empresas_activas" => 'Empresas Activas',
    "ventas_realizadas" => 'Ventas Realizadas',
    "compras_realizadas" => 'Compras Realizadas',
    "guias_realizadas" => 'Guias Realizadas',
    "productos_registrados" => 'Productos Realizados',
  ],

  'acciones' => [
    "empresas_ventas_pendientes" => 'Documentos Pendientes',
    "empresas_resumenes_pendientes"  => 'Resumenes Pendientes',
    "empresas_guias_pendientes"      => 'Guias Pendientes',
    "ordenes_pendientes"    => 'Ordenes Pendientes',
  ],
  
];