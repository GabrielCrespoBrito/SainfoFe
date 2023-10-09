<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
  # Acciones 
  const A_INDEX = "index";
  const A_SHOW = "show";
  const A_DELETE = "delete";
  const A_EDIT = "edit";
  const A_CREATE = "create";
  const A_REPORTE_MOVIMIENTO = "reportes-movimientos";
  const A_UPDATESTOCK = "actualizar-stock";
  const A_MENUDEO = "menudeo";
  const A_SHOWPRECIOS = "ver-precios";
  const A_UPDATEPRECIOS = "actualizar-precios";
  const A_UPDATEPRECIOSMASIVE = "actualizar-precios-masivamente";
  const A_UPDATEPRECIOSTIPOCAMBIO = "actualizar-precios-por-tipocambio";
  const A_IMPRIMIR = "imprimir";
  const A_PRODUCCIONMANUAL = "produccion-manual";
  const A_LIBERAR = "liberar";
  const A_ANULAR = "anular";
  const A_EMAIL = "email";
  const A_RESUMENES = "resumenes";
  const A_CONTINGENCIA = "contingencia";
  const A_CONSULTARDOCUMENTO = "consultar_documentos";
  const A_MODIFICAR_PRECIO = "modificar-precios";
  const A_VER_NOTAS_VENTA = "ver-notas-venta";
  const A_DESPACHO = "despacho";
  const A_TRASLADO = "traslado";
  const A_PAGOS = "pagos";
  const A_IMPORTARPRODUCTO = "importar-producto";
  const A_IMPORTARVENTA = "importar-venta";
  const A_EXPORTAR_COMPRAVENTA = "exporta-compra-venta";
  const A_CIERRE_MES = "cierre-mes";
  const A_PRODUCTOSTOCK = "productos-stock";
  const A_PARAMETRO = "parametros";

  # Reportes
  const A_COMPRAVENTA = "compra-venta";
  const A_FACTURACION = "facturacion";
  const A_TIPOPAGO = "venta-tipo-pago";
  const A_KARDEXFISICO = "kardex-fisico";
  const A_KARDEXVALORIZADO = "kardex-valorizado";
  const A_KARDEXPORFECHA = "kardex-por-fecha";
  const A_KARDEXTRASLADO = "kardex-traslado";
  const A_GUIAELECTRONICA = "guias-electronicas";
  const A_CONTABLEVENTASMENSUAL = "contable-ventas-mensual";
  const A_CONTABLECOMPRASMENSUAL = "contable-compras-mensual";
  const A_VENTA = "ventas";
  const A_COMPRA = "compras";
  const A_DETRACCION = "detraccion";
  const A_LISTAPRECIO = "listaprecio";
  const A_GUIA = "guia";
  const A_ENTIDAD = "cliente-proveedor";
  const A_CLIENTEDEUDA = "cliente-deuda";
  const A_UTILIDADESVENTAS = "utilidades-ventas";
  const A_INVENTARIOVALORIZADO = "inventario-valorizado";
  const A_PRODUCTOMASVENDIDO = "productos-mas-vendidos";
  const A_MEJORESCLIENTES = "mejores-clientes";
  const A_VENTASANUAL = "ventas-anual";
  const A_VENDEDORVENTAS = "vendedor-ventas";
  const A_VENDEDORESTADISTICA = "vendedor-estadisticas";
  const A_VENDEDORPRODUCTO = "vendedor-productos";
  const A_VENDEDOVENTARPRODUCTO = "vendedor-ventas-productos";
  
  // -------------------------------------------------------
  const A_UTILIDADESVENTAS2 = "utilidades-ventas-2";
  const A_REPORTE_COMPRA_VENTA = "reporte-compra-venta";
  const A_REPORTE = "reporte";
  const A_REPORTE_INGRESO_EGRESO = 'reporte-movimientos';
  const A_TOMAINVENTARIO = "toma-inventario";
  const A_VERCOSTOS = "ver-costos";
  const A_RECURSO = "recursos";
  const A_CUENTASPORCOBRAR = "cuentas-por-cobrar";
  const A_CUENTASPORPAGAR = "cuentas-por-pagar";
  const A_MOVIMIENTOS = "movimientos";
  const A_CREATE_MOVIMIENTOS = "create-movimientos";
  const A_EDIT_MOVIMIENTOS = "edit-movimientos";
  const A_APERTURAR = "aperturar";
  const A_APERTURADAS = "aperturadas";
  const A_REAPERTURAR =  "reaperturar";
  const A_CERRAR = "cerrar";
  const A_MEDIOPAGO = "medio-pago";

  # Recursos 
  const R_VENTA = "ventas";
  const R_CLIENTE = "clientes";
  const R_MARCA = "marcas";
  const R_FAMILIA = 'familias';
  const R_PRODUCTO = 'productos';
  const R_GRUPO = 'grupos';
  const R_LISTAPRECIO = 'lista-precios';
  const R_PREVENTA = 'cotizaciones';
  const R_ORDENCOMPRA = 'orden_compra';
  const R_COMPRA = 'compras';
  const R_REPORTE = 'reportes';
  const R_GUIASALIDA = 'guias-salidas';
  const R_GUIATRANSPORTISTA = 'guias-transportista';
  const R_GUIAINGRESO = 'guias-ingresos';
  const R_CAJA = "cajas";
  const R_BANCO = "bancos";
  const R_PAGO = "pagos";
  const R_CUENTA = "cuentas";
  const R_LOCAL = "locales";
  const R_FORMAPAGO = "locales";
  const R_VENDEDOR = "vendedores";
  const R_EMPRESATRANSPORTE = "empresa-transporte";
  const R_TRANSPORTISTA = "transportista";
  const R_UTILITARIO = "utilitarios";
  const R_MEDIOPAGO = "medios-pagos";
  const R_EMPRESA = "empresas";

  # Permisos de caja
  const CAJA_INDEX = 'index cajas';
  const CAJA_SHOW = 'show caja';
  const CAJA_CREATE = 'create caja';
  const CAJA_DELETE = 'delete caja';
  const CAJA_PENDIENTES = 'pendientes caja';
  const CAJA_VER_MONTOS = 'ver-montos';

  protected $data;

  public function run()
  {
  }

  /**
   * Funcion para devolver los nombres correctos de los permisos 
   * 
   */
  public static function getName(array $strs)
  {
    $permission_str = [];
    foreach ($strs as $str) {
      array_push($permission_str, constant("self::$str"));
    }

    return implode(' ', $permission_str);
  }

  public static function getNameForMiddleware(array $strs)
  {
    return 'permission:' . self::getName($strs);
  }


  public function getDataFormat($permiso, $group, bool $is_admin)
  {
    $descripcion = '';
    $permisos_parts = explode(' ', $permiso);
    foreach ($permisos_parts as $permiso_name) {
      $descripcion .= ' ' .  __('messages.' . $permiso_name);
    }

    $data = [
      'name' => $permiso,
      'group' =>  $group,
      'descripcion' => trim($descripcion),
      'is_admin' => $is_admin,
      'valid' => "1",
      'guard_name' => 'web'
    ];
    
    return $data;
  }

  /**
   * Obtener todos los permisos
   *
   * @return array
   */
  public function getPermissions()
  {
    $this->setVentasPermisos();
    $this->setClientePermisos();
    $this->setGuiasPermisos();
    $this->setMarcaPermisos();
    $this->setCajaPermisos();
    $this->setGruposPermisos();
    $this->setFamiliasPermisos();
    $this->setListaPreciosPermisos();
    $this->setUnidadesPermisos();
    $this->setProductosPermisos();
    $this->setPreventaPermisos();
    $this->setComprasPermisos();
    $this->setReportesPermisos();
    $this->setCuentasPermisos();
    $this->setLocalPermisos();
    $this->setVendedoresPermisos();
    $this->setFormaPagoPermisos();
    $this->setEmpresaTransportePermisos();
    $this->setTransportistasPermisos();
    $this->setUtilitariosPermisos();
    return $this->data;
  }


  public function setDatas($permissions, $group, bool $is_admin)
  {
    foreach ($permissions as $permission) {
      $permission_data = (array) $permission;
      $permiso_name = $permission_data[0];
      $permiso_group = $permission_data[1] ?? $group;
      $permiso_admin = $permission_data[2] ?? $is_admin;

      $this->data[] = $this->getDataFormat($permiso_name, $permiso_group, $permiso_admin);
    }
  }

  public function setClientePermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_CLIENTE),
      concat_space(self::A_SHOW, self::R_CLIENTE),
      concat_space(self::A_CREATE, self::R_CLIENTE),
      concat_space(self::A_EDIT, self::R_CLIENTE),
      concat_space(self::A_DELETE, self::R_CLIENTE),
    ], self::R_CLIENTE, false);
  }

  public function setMarcaPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_MARCA),
      concat_space(self::A_CREATE, self::R_MARCA),
      concat_space(self::A_EDIT, self::R_MARCA),
      concat_space(self::A_DELETE, self::R_MARCA),
    ], self::R_MARCA,  false);
  }

  public function setGruposPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_GRUPO),
      concat_space(self::A_CREATE, self::R_GRUPO),
      concat_space(self::A_EDIT, self::R_GRUPO),
      concat_space(self::A_DELETE, self::R_GRUPO),
    ], self::R_GRUPO,  false);
  }

  public function setFamiliasPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_FAMILIA),
      concat_space(self::A_CREATE, self::R_FAMILIA),
      concat_space(self::A_EDIT, self::R_FAMILIA),
      concat_space(self::A_DELETE, self::R_FAMILIA),
    ], self::R_FAMILIA,  false);
  }


  public function setListaPreciosPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_LISTAPRECIO),
      concat_space(self::A_CREATE, self::R_LISTAPRECIO),
      concat_space(self::A_EDIT, self::R_LISTAPRECIO),
      concat_space(self::A_DELETE, self::R_LISTAPRECIO),
    ], self::R_LISTAPRECIO,  false);
  }

  public function setUnidadesPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_LISTAPRECIO),
      concat_space(self::A_CREATE, self::R_LISTAPRECIO),
      concat_space(self::A_EDIT, self::R_LISTAPRECIO),
      concat_space(self::A_DELETE, self::R_LISTAPRECIO),
    ], self::R_LISTAPRECIO,  false);
  }

  public function setComprasPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_COMPRA),
      concat_space(self::A_SHOW, self::R_COMPRA),
      concat_space(self::A_CREATE, self::R_COMPRA),
      concat_space(self::A_EDIT, self::R_COMPRA),
      concat_space(self::A_DELETE, self::R_COMPRA),
      concat_space(self::A_INDEX, self::R_PAGO, self::R_COMPRA),
      concat_space(self::A_SHOW, self::R_PAGO, self::R_COMPRA),
      concat_space(self::A_CREATE, self::R_PAGO, self::R_COMPRA),
      concat_space(self::A_EDIT, self::R_PAGO, self::R_COMPRA),
      concat_space(self::A_DELETE, self::R_PAGO, self::R_COMPRA),

    ], self::R_COMPRA,  false);
  }
  public function setCuentasPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_CUENTA),
      concat_space(self::A_CREATE, self::R_CUENTA),
      concat_space(self::A_EDIT, self::R_CUENTA),
      concat_space(self::A_DELETE, self::R_CUENTA),
      concat_space(self::A_APERTURAR, self::R_CUENTA),
      concat_space(self::A_APERTURADAS, self::R_CUENTA),
      concat_space(self::A_REAPERTURAR, self::R_CUENTA),
      concat_space(self::A_CERRAR, self::R_CUENTA),
      concat_space(self::A_DELETE, self::R_CUENTA),
      concat_space(self::A_DELETE, self::A_APERTURADAS, self::R_CUENTA),
    ], self::R_CUENTA,  false);
  }

  public function setProductosPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_PRODUCTO),
      concat_space(self::A_CREATE, self::R_PRODUCTO),
      concat_space(self::A_EDIT, self::R_PRODUCTO),
      concat_space(self::A_DELETE, self::R_PRODUCTO),
      concat_space(self::A_REPORTE_MOVIMIENTO, self::R_PRODUCTO),
      concat_space(self::A_UPDATESTOCK, self::R_PRODUCTO),
      concat_space(self::A_MENUDEO, self::R_PRODUCTO),
      concat_space(self::A_SHOWPRECIOS, self::R_PRODUCTO),
      concat_space(self::A_UPDATEPRECIOS, self::R_PRODUCTO),
      concat_space(self::A_UPDATEPRECIOSMASIVE, self::R_PRODUCTO),
      concat_space(self::A_UPDATEPRECIOSTIPOCAMBIO, self::R_PRODUCTO),
      concat_space(self::A_PRODUCCIONMANUAL, self::R_PRODUCTO),
      concat_space(self::A_TOMAINVENTARIO, self::R_PRODUCTO),concat_space(self::A_VERCOSTOS, self::R_PRODUCTO),

    ], self::R_PRODUCTO,  false);
  }


  public function setPreventaPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_PREVENTA),
      concat_space(self::A_SHOW, self::R_PREVENTA),
      concat_space(self::A_CREATE, self::R_PREVENTA),
      concat_space(self::A_EDIT, self::R_PREVENTA),
      concat_space(self::A_DELETE, self::R_PREVENTA),
      concat_space(self::A_IMPRIMIR, self::R_PREVENTA),
      concat_space(self::A_LIBERAR, self::R_PREVENTA),
      concat_space(self::A_MODIFICAR_PRECIO, self::R_PREVENTA),
    ], self::R_PREVENTA,  false);


    // Orden de Compra
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_ORDENCOMPRA),
      concat_space(self::A_SHOW, self::R_ORDENCOMPRA),
      concat_space(self::A_CREATE, self::R_ORDENCOMPRA),
      concat_space(self::A_EDIT, self::R_ORDENCOMPRA),
      concat_space(self::A_DELETE, self::R_ORDENCOMPRA),
      concat_space(self::A_IMPRIMIR, self::R_ORDENCOMPRA),

    ], self::R_ORDENCOMPRA,  false);


  }

  public function setUtilitariosPermisos()
  {
    $this->setDatas([
      concat_space(self::A_PARAMETRO, self::R_EMPRESA),
      concat_space(self::A_IMPORTARPRODUCTO, self::R_UTILITARIO),
      concat_space(self::A_IMPORTARVENTA, self::R_UTILITARIO),
      concat_space(self::A_EXPORTAR_COMPRAVENTA, self::R_UTILITARIO),
      concat_space(self::A_CIERRE_MES, self::R_UTILITARIO),
      concat_space(self::R_MEDIOPAGO, self::R_UTILITARIO),
    ], self::R_UTILITARIO,  false);
  }


  public function setReportesPermisos()
  {
    $this->setDatas([
      concat_space(self::A_COMPRAVENTA, self::R_REPORTE),
      concat_space(self::A_COMPRAVENTA, self::R_REPORTE),
      concat_space(self::A_FACTURACION, self::R_REPORTE),
      concat_space(self::A_TIPOPAGO, self::R_REPORTE),
      concat_space(self::A_KARDEXFISICO, self::R_REPORTE),
      concat_space(self::A_KARDEXVALORIZADO, self::R_REPORTE),
      concat_space(self::A_KARDEXPORFECHA, self::R_REPORTE),
      concat_space(self::A_KARDEXTRASLADO, self::R_REPORTE),
      concat_space(self::A_GUIAELECTRONICA, self::R_REPORTE),
      concat_space(self::A_CONTABLEVENTASMENSUAL, self::R_REPORTE),
      concat_space(self::A_CONTABLECOMPRASMENSUAL, self::R_REPORTE),
      concat_space(self::A_VENTA, self::R_REPORTE),
      concat_space(self::A_COMPRA, self::R_REPORTE),
      concat_space(self::A_DETRACCION, self::R_REPORTE),
      concat_space(self::A_LISTAPRECIO, self::R_REPORTE),
      concat_space(self::A_GUIA, self::R_REPORTE),
      concat_space(self::A_ENTIDAD, self::R_REPORTE),
      concat_space(self::A_CLIENTEDEUDA, self::R_REPORTE),
      concat_space(self::A_UTILIDADESVENTAS, self::R_REPORTE),
      concat_space(self::A_INVENTARIOVALORIZADO, self::R_REPORTE),
      concat_space(self::A_PRODUCTOMASVENDIDO, self::R_REPORTE),
      concat_space(self::A_MEJORESCLIENTES, self::R_REPORTE),
      concat_space(self::A_VENTASANUAL, self::R_REPORTE),
      concat_space(self::A_VENDEDORVENTAS, self::R_REPORTE),
      concat_space(self::A_VENDEDORESTADISTICA, self::R_REPORTE),
      concat_space(self::A_VENDEDORPRODUCTO, self::R_REPORTE),
      concat_space(self::A_VENDEDOVENTARPRODUCTO, self::R_REPORTE),
      concat_space(self::A_PRODUCTOSTOCK, self::R_REPORTE),
      concat_space(self::A_UTILIDADESVENTAS2, self::R_REPORTE),
    ], self::R_REPORTE,  false);
  }

  public function setVentasPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_VENTA),
      concat_space(self::A_SHOW, self::R_VENTA),
      concat_space(self::A_CREATE, self::R_VENTA),
      concat_space(self::A_ANULAR, self::R_VENTA),
      concat_space(self::A_IMPRIMIR, self::R_VENTA),
      concat_space(self::A_EMAIL, self::R_VENTA),
      concat_space(self::A_RECURSO, self::R_VENTA),
      concat_space(self::A_RESUMENES, self::R_VENTA),
      concat_space(self::A_CONTINGENCIA, self::R_VENTA),
      concat_space(self::A_PAGOS, self::R_VENTA),
      concat_space(self::A_CONSULTARDOCUMENTO, self::R_VENTA),
      concat_space(self::A_INDEX, self::R_PAGO, self::R_VENTA),
      concat_space(self::A_SHOW, self::R_PAGO, self::R_VENTA),
      concat_space(self::A_CREATE, self::R_PAGO, self::R_VENTA),
      concat_space(self::A_EDIT, self::R_PAGO, self::R_VENTA),
      concat_space(self::A_DELETE, self::R_PAGO, self::R_VENTA),
      concat_space(self::A_CONSULTARDOCUMENTO, self::R_VENTA),
      concat_space(self::A_MODIFICAR_PRECIO, self::R_VENTA),
      concat_space(self::A_VER_NOTAS_VENTA, self::R_VENTA),
    ], self::R_VENTA,  false);
  }

  public function setGuiasPermisos()
  {
    // Salida
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_GUIASALIDA),
      concat_space(self::A_SHOW, self::R_GUIASALIDA),
      concat_space(self::A_CREATE, self::R_GUIASALIDA),
      concat_space(self::A_DELETE, self::R_GUIASALIDA),
      concat_space(self::A_ANULAR, self::R_GUIASALIDA),
      concat_space(self::A_IMPRIMIR, self::R_GUIASALIDA),
      concat_space(self::A_EMAIL, self::R_GUIASALIDA),
      concat_space(self::A_RECURSO, self::R_GUIASALIDA),
      concat_space(self::A_DESPACHO, self::R_GUIASALIDA),
      concat_space(self::A_TRASLADO, self::R_GUIASALIDA),
    ], self::R_GUIASALIDA,  false);

    // Transportista
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_SHOW, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_CREATE, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_DELETE, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_ANULAR, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_IMPRIMIR, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_EMAIL, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_RECURSO, self::R_GUIATRANSPORTISTA),
      concat_space(self::A_DESPACHO, self::R_GUIATRANSPORTISTA),
    ], self::R_GUIATRANSPORTISTA,  false);


    // Ingreso
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_GUIAINGRESO),
      concat_space(self::A_SHOW, self::R_GUIAINGRESO),
      concat_space(self::A_CREATE, self::R_GUIAINGRESO),
      concat_space(self::A_IMPRIMIR, self::R_GUIAINGRESO),
    ], self::R_GUIAINGRESO,  false);
  }


  public function setLocalPermisos()
  {
    // Salida
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_LOCAL),
      concat_space(self::A_CREATE, self::R_LOCAL),
      concat_space(self::A_EDIT, self::R_LOCAL),
      concat_space(self::A_DELETE, self::R_LOCAL),
    ], self::R_LOCAL,  false);
  }

  public function setFormaPagoPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_FORMAPAGO),
      concat_space(self::A_CREATE, self::R_FORMAPAGO),
      concat_space(self::A_EDIT, self::R_FORMAPAGO),
      concat_space(self::A_DELETE, self::R_FORMAPAGO),
    ], self::R_FORMAPAGO,  false);
  }

  public function setEmpresaTransportePermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_EMPRESATRANSPORTE),
      concat_space(self::A_CREATE, self::R_EMPRESATRANSPORTE),
      concat_space(self::A_EDIT, self::R_EMPRESATRANSPORTE),
      concat_space(self::A_DELETE, self::R_EMPRESATRANSPORTE),
    ], self::R_EMPRESATRANSPORTE,  false);
  }


  public function setTransportistasPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_TRANSPORTISTA),
      concat_space(self::A_CREATE, self::R_TRANSPORTISTA),
      concat_space(self::A_EDIT, self::R_TRANSPORTISTA),
      concat_space(self::A_DELETE, self::R_TRANSPORTISTA),
    ], self::R_TRANSPORTISTA,  false);
  }

  public function setVendedoresPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_VENDEDOR),
      concat_space(self::A_CREATE, self::R_VENDEDOR),
      concat_space(self::A_EDIT, self::R_VENDEDOR),
      concat_space(self::A_DELETE, self::R_VENDEDOR),
    ], self::R_VENDEDOR,  false);
  }

  public function setCajaPermisos()
  {
    $this->setDatas([
      concat_space(self::A_INDEX, self::R_CAJA),
      concat_space(self::A_SHOW, self::R_CAJA),
      concat_space(self::A_CREATE, self::R_CAJA),
      concat_space(self::A_REPORTE_COMPRA_VENTA, self::R_CAJA),
      concat_space(self::A_REPORTE_INGRESO_EGRESO, self::R_CAJA),
      concat_space(self::A_CUENTASPORPAGAR, self::R_CAJA),
      concat_space(self::A_CUENTASPORCOBRAR, self::R_CAJA),
      concat_space(self::A_MOVIMIENTOS, self::R_CAJA),
      concat_space(self::A_CREATE_MOVIMIENTOS, self::R_CAJA),
      concat_space(self::A_EDIT_MOVIMIENTOS, self::R_CAJA),
      concat_space(self::CAJA_VER_MONTOS, self::R_CAJA),
    ], self::R_CAJA,  false);
  }

  public function getSecundaryUserPermissions()
  {
    $this->setVentasPermisos();
    $this->setClientePermisos();
    $this->setGuiasPermisos();
    $this->setCajaPermisos();
    $this->setProductosPermisos();
    $this->setPreventaPermisos();
    $this->setComprasPermisos();

    return $this->data;
  }
}
