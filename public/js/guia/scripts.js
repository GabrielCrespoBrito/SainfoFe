
var producto_input_focus = null;
var tr_selected;
var enter_accion = false;
var tr_pago = {};
var IGV_VALUE = 1.18;
var modal_cliente = "#modalSelectCliente";
var modal_producto = "#modalSelectCliente";
var modal_factura = "#modalSelectFactura";
var modal_tipodocumento = "#modalSelectTipoDocumentoPago";
var modales_select = $(".modal-seleccion");
var table_clientes = $("#datatable-clientes");
var tipo_guardado = null;
var items_agregate = [];
var action_item = "create";
var table_items = $("#table-items");
var current_product_data = null;
var focus_orden = {
  'cliente_documento': 'forma_pago',
  'forma_pago': 'moneda',
  'moneda': 'tipo_cambio',
  'doc_ref': 'fecha_emision',
  'tipo_cambio': 'fecha_emision',
  'producto_unidad': 'producto_cantidad',
  'producto_cantidad': 'producto_precio',
  'producto_precio': agregar_item,
  'producto_nombre': verificar_producto_unidad,
  'producto_dct': agregar_item,
  'producto_igv': agregar_item,
  'producto_isc': agregar_item,
  'producto_percepcion': agregar_item,
  "ref_documento": "ref_serie",
  "ref_serie": "ref_numero",
  "ref_numero": verifiy_factura_number,
  "ref_fecha": 'ref_motivo',
  "ref_motivo": 'producto_nombre',
  "ref_tipo": 'producto_nombre',
};

/**
 * Establecer cual sera elemento del que se va a poner el foco por defecto para la busqueda del producto
 */

function setProductoInputSearchDefaultFocus() {
  if (cursor_producto == "1") {
    producto_input_focus = $("[name=producto_nombre]");
  }

  else if (cursor_producto == "0") {
    producto_input_focus = $("[name=producto_codigo]");
  }

  else {
    producto_input_focus = $("[name=producto_codigo]");
  }
}


function defaultErrorAjaxFunc(data) {
  console.log("error ajax", data);
  let errors = data.responseJSON.errors;
  let mensaje = data.responseJSON.message;

  let erros_arr = [];
  for (prop in errors) {
    for (let i = 0; i < errors[prop].length; i++) {
      erros_arr.push(errors[prop][i]);
    }
  }
  notificaciones(erros_arr, 'error', mensaje)
}

function verificar_producto_unidad(fromTable = true) {
  if (fromTable) {
    $("[name=producto_unidad]").focus();
  }
}

function show_hide_modal(id_modal, show = true, static_ = false, callback = false) {
  if (typeof show !== "boolean") {
    throw Error("true o false son los valores admitidos para activar o desactivar el modal, no: " + show);
  }

  let modal = $("#" + id_modal);
  if (!static_) {
    modal.modal(show ? "show" : "hide");
  }

  else {
    modal.modal({ backdrop: "static" });
  }

  callback ? callback() : null;
}

function calcular_item(data) {
  let dcto = Number(data.DetDcto);
  let igv_item = data.DetIGVP == "0" ? "0" : (Number(data.DetPrec) / Number(IGV_VALUE));
  let resultado = {
    descuento: 0,
    valor_igv_x_item: igv_item,
    valor_igv_total: igv_item * Number(data.DetCant),
    valor_venta_total: 0,
    igv: 0,
    valor_venta: 0,
  };

  // Calcular descuento 
  if (data.DetDcto == "0") {
    resultado.descuento = dcto;
    // console.log("(IGVP > 0)" , resultado.valor_igv_total);      
    resultado.descuento = (resultado.valor_igv_total / 100) * dcto;
  };

  resultado.valor_venta_total = resultado.valor_igv_total - resultado.descuento;

  // IGV 
  resultado.igv = resultado.valor_venta_total * 0.18;


  return resultado;
}

function sum_cant() {
  let info = {
    gravadas: 0,
    inafectas: 0,
    exoneradas: 0,
    gratuitas: 0,
    descuento: 0,
    pagado: 0,
    isc: 0,
    igv: 0,
    total_documento: 0,
    percepcion: 0,
    total_importe: 0,
    total_cantidad: 0,
    total_peso: 0,
  };

  let data;

  $("#table-items tbody tr").each(function (index, dom) {

    data = JSON.parse($(dom).attr('data-info'));

    let precio = Number(fixedValue(data.DetPvta));

    // console.log("Data del item" , data);
    let oper_resultados = calcular_item(data);

    switch (data.DetBase) {
      case 'GRAVADA':
        info.gravadas += oper_resultados.valor_venta_total;
        break;
      case 'INAFECTA':
        info.inafectas += precio;
        break;
      case 'EXONERADA':
        info.exoneradas += precio;
        break;
      case 'GRATUITA':
        info.gratuitas += precio;
        break;
    }

    let cantidad = Number(fixedValue(data.DetCant));;
    let peso = Number(fixedValue(data.DetPeso));

    info.total_cantidad += cantidad;
    info.total_peso += peso;
    info.descuento += oper_resultados.descuento;
    info.igv += oper_resultados.igv;
    info.total_importe += data.DetBase == 'GRATUITA' ? 0 : Number(fixedValue(data.DetImpo));

  });

  info.total_documento = info.total_importe;
  return info;
}

function cantidad_letras(num) {
  $(".cifra_cantidad").text(NumeroALetras(num));
}

function fixValue(value, decimals = 2) {
  return Number(value).toFixed(decimals)
}

function poner_totales_cant() {
  let info = sum_cant();
  for (prop in info) {
    $("[data-name=" + prop + "]").val(fixValue(info[prop]));
  }

  cantidad_letras(info.total_importe);
}


function fixedNumber(v, codigo = false) {
  return isNaN(v) ? v : codigo ? v : Number(v).toFixed(2);
}

function tdCreate(inputNameOrValue, getFromInput = true, campo = "") {
  let value;
  let td = $("<td></td>");
  var campo_sindecimales = false;

  if (getFromInput) {
    value = $("[name=" + inputNameOrValue + "]").val()
  }
  else {
    value = inputNameOrValue;
  }

  var camposFiltados = "TieCodi,id_pago,TpgCodi,ItenNum,itemNum,TieCodi,UniCodi,Linea,Nombre,Marca,Moneda,NroDocumento,DetCodi,UniCodi,Fecha,UniCodi".split(",");

  if (camposFiltados.includes(campo)) {
    campo_sindecimales = true;
  }

  if (campo_sindecimales) {
    return td
      .text(value)
      .attr("data-campo", campo);
  }

  else {
    return td
      .text(fixedNumber(value, campo_sindecimales))
      .attr("data-campo", campo);
  }


}

function serie_documento_ok(data) {
  // table_facturas.draw();
  select_tabla_facturas()
  $("[name=ref_motivo]").focus();
};

function serie_documento_error(data) {
  notificaciones("Serie documento no encontrado", "danger");
  // console.log("serie error" , data);
};

function verifiy_factura_number() {
  if (!verifyInputCliente()) {
    notiYFocus("cliente_documento", "Seleccione un cliente");
    return;
  }

  if (!$("[name=ref_documento]").val().length) {
    notiYFocus("ref_documento", "Tiene que introducir primero el tipo de documento");
    return;
  }

  if (!$("[name=ref_serie]").val().length) {
    notiYFocus("ref_serie", "Tiene que introducir primero la serie del documento");
    return;
  }

  let buscar = $("[name=ref_numero]").val();
  // table_facturas.draw();


  if (!$(".modal.fade.in").length) {
    show_modal("show", "#modalSelectFactura");
  }

  return;
}

function verifyInputCliente() {
  return $("[name=cliente_documento]").val().length;
}

function verifiy_tipo_documento() {
  if (verifyInputCliente()) {
    show_modal("show", modalSelectTipoDocumentoPago);
  }
  else {
    notiYFocus("cliente_documento", "Seleccione un cliente");
  }
}

function agregar_dias() {
  let dias = Number($("[name=forma_pago] option:selected").attr('data-dias'));
  let fecha_inicial = $("[name=fecha_emision]").attr("data-fecha_inicial");

  if (dias == 0) {
    $("[name=fecha_referencia]").val(fecha_inicial);
    return;
  }

  else {
    let d = new Date(new Date(fecha_inicial).setDate(dias));
    $("[name=fecha_referencia]").datepicker("update", d);
  }

  console.info("sumando dias", dias, fecha_inicial);
}

function date() {
  $('.datepicker').datepicker({
    autoclose: true,
    language: 'es',
  });
}

function validateNumber(num, inputName = false) {
  let number = inputName ? $("[name=" + inputName + "]").val() : num;
  return (number.length != 0 && reg_number.test(number));
}

function validateIsNotNumber(param1, param2 = false) {
  return !validateNumber(param1, param2);
}

function validateDocumentoRef() {
  if (validateIsNotNumber("", "ref_documento")) {
    notiYFocus("tipo_cambio", "Es obligatorio poner el tipo de documento, cuando es nota de credito");
  }

  else if (!$("[name=ref_documento]").val().length) {
    notiYFocus("tipo_cambio", "Es obligatorio poner el tipo de documento, cuando es nota de credito");
  }
}

function notiYFocus(inputFocus, notificacionMensaje, notificacionTipo = "error") {
  $("[name=" + inputFocus + "]").focus();
  notificaciones(notificacionMensaje, notificacionTipo);
}

function validarItem() {
  let resp = true;
  // cl( "aaa" , $("[name=producto_codigo]").val() );
  if ($("[name=producto_codigo]").val() == "") {
    notiYFocus("producto_codigo", "Ponga el codigo del producto")
    resp = false;
  }

  else if (!$("[name=producto_nombre]").val().length) {
    notiYFocus("producto_nombre", "No puede dejar vacia la descripción del producto");
    resp = false;
  }

  else if (validateIsNotNumber("", "producto_cantidad")) {
    notiYFocus("producto_cantidad", "La cantidad del producto tiene que ser un numero");
    resp = false;
  }

  else if (validateIsNotNumber("", "producto_precio")) {
    notiYFocus("producto_precio", "El precio del producto tiene que ser un numero");
    resp = false;
  }

  if (validateIsNotNumber("", "producto_importe")) {
    notiYFocus("producto_importe", "El importe de la compra no puede estar vacia");
    resp = false;
  }

  return resp;
}

function verificarExistencia() {

}

function agregar_item() {
  if (executing_ajax) {
    return;
  }

  if (!create) {
    return;
  }

  if (!validarItem()) {
    return;
  }

  // stock
  let stock = Number($("[name=producto_stock]").val());
  let cantidad = Number($("[name=producto_cantidad]").val());
  let precio = Number($("[name=producto_precio]").val());

  if (modulo_manejo_stock) {
    if (cantidad > stock) {
      if (!confirm("Stock disponible es menor que la cantidad requerida, desea continuar?")) {
        $("[name=producto_cantidad]").focus().select();
        return;
      }
    }
  }

  let info;

  if (action_i() == "create") {
    info = {
      Unidades: current_product_data.unidades,
      Marca: current_product_data.marca.MarNomb,
      MarCodi: current_product_data.marca.MarCodi,
      TieCodi: current_product_data.producto.tiecodi,
      DetPeso: current_product_data.producto.ProPeso,
      DetCodi: current_product_data.producto.ProCodi,
    };
  }

  else {
    let trInfo = $("tr.seleccionando").data();
    info = {
      Unidades: trInfo.unidades,
      Marca: trInfo.info.Marca,
      MarCodi: trInfo.info.MarCodi,
      TieCodi: trInfo.info.TieCodi,
      DetPeso: trInfo.info.ProPeso,
      DetCodi: trInfo.info.DetCodi,
      Linea: trInfo.info.Linea,

    };
  };
  info.is_guia = true;
  info.DetCome = $("[name=commentario]").val();
  info.UniCodi = $("[name=producto_unidad]").val();
  info.DetUniNomb = $("[name=producto_unidad] option:selected").text();
  info.DetNomb = $("[name=producto_nombre]").val();
  info.DetUni = $("[name=producto_unidad]").val();
  info.DetCant = $("[name=producto_cantidad]").val();
  info.DetPrec = $("[name=producto_precio]").val();
  info.DetDcto = $("[name=producto_dct]").val();
  info.DetPercP = $("[name=producto_percepcion]").val();
  info.DetPercV = $("[name=producto_percepcion_importe]").val();
  info.DetBase = $("[name=producto_igv] option:selected").val();
  info.DetIGVP = $("[data-namedb=proigvv]").val();
  info.DetIGVV = $("[name=producto_igv_total]").val();
  info.DetISC = $("[name=producto_isc]").val();
  info.DetPeso = info.DetPeso * info.DetCant;
  info.DetISP = $("[name=producto_isc_other]").val();
  info.DetImpo = $("[name=producto_importe]").val();
  info.incluye_igv = 1;

  console.log("info en accion", action_i(), info);

  let success_func = action_i() == "create" ? add_item(info) : edit_item(info);
  return;

  let funcs = {
    success: success_func,
  }
  ajaxs(info, url_verificar_item_info, funcs);
}

function error_item() {
  // console.log("ay un error en el item");
}

function modificar_tr(info, tr) {
  let campo = false;
  let campo_sindecimales

  for (prop in info) {
    if (prop == "TieCodi" ||
      prop == "itemNum" ||
      prop == "UniCodi",
      prop == "DetUniNomb") {
      campo_sindecimales = true;
    }

    if (campo_sindecimales) {
      tr.find("[data-campo=" + prop + "]").text(info[prop]);
    }
    else {
      tr.find("[data-campo=" + prop + "]").text(fixedValue(info[prop], campo));
    }
  }
}


function edit_item(info) {
  notificaciones("Item modificado exitosamente", "success");
  let tr = $("tr.seleccionando");

  tr.hide(500, function () {
    modificar_tr(info, tr);
    tr
      .show(500)
      .removeClass("seleccionando");
  });

  // DetUniNomb

  // console.log("editando item" , info);
  tr.attr('data-info', JSON.stringify(info));
  cleanInputsGroup("producto", quitar_unidad);
  action_item = "create";
  poner_totales_cant();
}

function add_item(info, fromForm = false) {
  let unidades = fromForm ? current_product_data.unidades : info.Unidades;
  let tbody = table_items.find("tbody");

  let html_actions = "<a href='#' class='btn modificar_item btn-xs btn-primary'> <span class='fa fa-pencil'></span> </a><a href='#' class='btn eliminar_item btn-xs btn-danger'> <span class='fa fa-trash'></span> </a>";

  let trItem = $("<tr></tr>")
    .addClass('tr_item')
    .attr({
      'data-info': JSON.stringify(info),
      'data-unidades': JSON.stringify(unidades)
    });

  let itemNume = table_items.find("tbody tr").length + 1;
  itemNume = itemNume < 10 ? ("0" + itemNume) : itemNume;
  let tdItem = tdCreate(itemNume, false, "itemNum");
  let tdCod = tdCreate(info.DetCodi, false, "DetCodi");
  let tdUni = tdCreate(info.DetUniNomb, false, "DetUniNomb");
  let tdDes = tdCreate(info.DetNomb, false, "DetNom");
  let tdMarca = tdCreate(info.Marca, false, "Marc");
  let tdCant = tdCreate(info.DetCant, false, "DetCant");
  let tdPrecio = tdCreate(info.DetPrec, false, "DetPrec");
  let tdImporte = tdCreate(info.DetImpo, false, "DetImpo");
  let tdAccion = tdCreate("", false, "Acciones");
  tdAccion.html(html_actions);

  trItem.append(
    tdItem,
    tdCod,
    tdUni,
    tdDes,
    tdMarca,
    tdCant,
    tdPrecio,
    tdImporte,
    tdAccion
  );

  tbody.append(trItem);

  let total = Number($("[name=producto_importe]").val());
  let total_importe = total + Number(info.DetPrec);
  $("[name=total_importe]").val(total);

  cleanInputsGroup("producto", quitar_unidad);
  $("[name=producto_nombre]").focus();

  habilitarDesactivarSelect("tipo_cambio", false);
  habilitarDesactivarSelect("moneda", false);

  poner_totales_cant();
  descuento_global();
}

function habilitarDesactivarSelect(select_name, activar = true) {
  let select = $("[name=" + select_name + "]");

  if (select.length) {
    activar ? select.removeAttr('disabled') : select.attr('disabled', 'disabled');
  }
  else {
    activar ? $("." + select_name).removeClass('disabled') : $("." + select_name).addClass('disabled')
  }
}


function show_modal(action = "show", modal = "#modalAccion", backdrop = true) {
  if (action == "hide") {
    $(modal).modal(action);
  }
  else {
    $(modal).modal({
      show: true,
      backdrop: backdrop
    });

  }
}

function buscar_en_table(buscar, table = table_clientes) {
  table.search(buscar).draw();
}

function findInput(inputName) {
  return $("[name=" + inputName + "]")
}

function buscar_cliente() {

  let nro_documento = findInput("cliente_documento").val();
  buscar_en_table(nro_documento, table_clientes);
  show_modal("show", "#modalSelectCliente");
}

function select_first_ele(table) {
  if (!table.find("tbody tr").find(".dataTables_empty").length) {
    table.find("tbody tr").eq(0).addClass('select');
  }
}

function select_tabla_productos() {
  select_first_ele($("#datatable-productos"));
}

function select_tabla_facturas() {
  select_first_ele($("#datatable-factura_select"));
}


function select_tabla_clientes() {
  select_first_ele($("#datatable-clientes"));
}

function select_tabla_cotizacion() {
  select_first_ele($("#datatable-cotizacion_select"));
}

function select_tabla_tipodocumento() {
  select_first_ele($("#datatable-tipopago_select"));
}


function price_moneda(data) {
  console.log("poniendo precio a moneda")
}

function seleccionando_ele_table(tr) {
  let table = tr.parents("table");
  let trSelect = $("tr.select", table);

  if (trSelect.length) {

    var allTrTable = trSelect.parents("table").find("tbody tr").toArray();
    if (key == 40) {
      if (trSelect.index() != allTrTable.length - 1) {
        trSelect.removeClass("select");
        $(allTrTable[$(trSelect).index() + 1]).addClass("select");
      }
    }

    else {
      if (trSelect.index()) {
        trSelect.removeClass("select");
        $(allTrTable[$(trSelect).index() - 1]).addClass("select");
      }
    }
  }
}

function subir_bajar_table(key, modal) {
  let trSelect = $("table tr.select", modal);
  if (trSelect.length) {
    var allTrTable = trSelect.parents("table").find("tbody tr").toArray();
    if (key == 40) {
      if (trSelect.index() != allTrTable.length - 1) {
        trSelect.removeClass("select");
        $(allTrTable[$(trSelect).index() + 1]).addClass("select");
      }
    }
    else {
      if (trSelect.index()) {
        trSelect.removeClass("select");
        $(allTrTable[$(trSelect).index() - 1]).addClass("select");
      }
    }
  }
}

function poner_data_inputs(
  data,
  no_adicional = true,
  adicional = null,
  name_busqueda = "data-namedb"
) {
  for (prop in data) {

    if (no_adicional !== true && no_adicional[prop]) {
      no_adicional[prop](data, adicional);
    }

    else {
      let ele = $("[" + name_busqueda + "=" + prop + "]");
      $("[" + name_busqueda + "=" + prop + "]").val(data[prop]);
    }

  }
}

function add_to_selected(select, data, name_value, text_value, adicional_ifo = []) {
  // <option name="name_value" > text_value </option>
}

// seleccionar una opción por defecto al select
function select_value(select_name, value) {
  let select = $("[name=" + select_name + "]");
  select.find('opcion').prop('selected', false);
  select.find("option[value=" + value + "]").prop('selected', true)
}

function agregarASelect(data, select, name_codi, name_text, adicional_info = []) {
  let select_agregar = $("[name=" + select + "]");
  select_agregar.empty();

  for (let i = 0; i < data.length; i++) {
    let actual_data = data[i];

    let option = $("<option></option>")
      .attr('value', actual_data[name_codi])
      .text(actual_data[name_text]);

    if (adicional_info.length) {
      for (let i = 0; i < adicional_info.length; i++) {
        let info = adicional_info[i];
        option.attr(info[0], actual_data[info[1]]);
      }
    }
    select_agregar.append(option);
  }
}


function is_nota(t) {
  let a = {
    "credito": "NOTA DE CREDITO",
    "debito": "NOTA DE DEBITO",
  }

  return $("[name=tipo_documento] option:selected").text().trim() === a[t];
}

function cambiar_tipo_documento() {
  let tipo_documento = $("[name=tipo_documento] option:selected");
  let data = eval(tipo_documento.attr('data-series'));
  let optionDefault = tipo_documento.attr('data-series');
  let div_datos = $(".div_datos_referenciales");

  agregarASelect(
    data,
    'serie_documento',
    'nombre',
    'id',
    [['data-codigo', 'nuevo_codigo']],
  );

  if (is_nota('debito') || is_nota('credito')) {
    $(".group_ref").removeAttr('disabled', 'disabled');
    div_datos.removeClass('block hide');
  }
  else {
    div_datos.addClass('block hide');
    $(".group_ref")
      .attr('disabled', 'disabled')
      .val('');
  }

}

function poner_data_cliente(data) {

  console.log("poner_data_cliente", data);
  let funcs = {
    'tipo_documento_c': poner_tipo_cliente,
  }

  poner_data_inputs(data, funcs);
  show_modal("hide", modal_cliente);
  nextFocus("cliente_documento");
}

function poner_tipo_cliente(data) {
  let tipo = data.tipo_documento_c.TdocNomb;
  $("[name=tipo_documento_c]").val(tipo);
  // console.log( " poner_El tip ode cliente" , data );
}


function poner_unidades(data) {
  let unidades = data.unidades;
  // console.log( "unidades" , unidades );
  $("[name=producto_unidad]").empty();

  for (let i = 0; i < unidades.length; i++) {
    let option = $("<option></option>")
      .attr('value', unidades[i].Unicodi)
      .text(unidades[i].UniAbre);

    $("[name=producto_unidad]").append(option)
  };
}

function set_precio() {
  let codigo_moneda, unidades;

  if (current_product_data == null) {
    return;
  }

  if (action_i() == "create") {
    codigo_moneda = current_product_data.producto.moncodi;
    unidades = current_product_data.unidades;
  }
  else {
    codigo_moneda = current_product_data.producto.moncodi;
    unidades = current_product_data.unidades;
  }

  // console.log( "codigo_moneda" , codigo_moneda );
  let precio;
  let moneda = $("[name=moneda] option[value=" + codigo_moneda + "]").text();
  let producto_unidad = $("[name=producto_unidad] option:selected");
  let unidad_select = null;

  let is_sol = Number($("[name=moneda] option:selected").attr('data-esSol'));

  for (let i = 0; i < unidades.length; i++) {
    if (unidades[i].UniAbre == producto_unidad.text()) {
      unidad_select = unidades[i];
      break;
    }
  }

  precio = is_sol ? unidad_select.UNIPUVS : unidad_select.UniPUVD;

  $("[name=producto_precio]").val(precio);
  calcular_importe();
}

function dcto_defecto(data) {
  if (data.ProDct1 === null || data.ProDct1 === "") {
    $("[name=producto_dct]").val(0);
  }

}

function poner_data_producto(data) {
  show_modal("hide", "#modalSelectProducto");
  data.producto.ProUnidades = null;
  $("[name=producto_cantidad]").val(1);
  let funcs_agregar = {
    "ProUnidades": poner_unidades,
    "ProDcto1": dcto_defecto,
  };

  current_product_data = data;
  poner_data_inputs(data.producto, funcs_agregar);
  // nextFocus("producto_nombre");
  $("[name=producto_nombre]")
    .select()
    .focus();

  set_precio();

  let localId = Number($(".informacion_empresa-local option:selected").attr('data-id'));

  let stock = current_product_data.producto['prosto' + localId];
  $("[name=producto_stock]").val(stock);
  console.log({ localId, current_product_data, stock })
}

function tipodocumento_selected(data) {
  $("[name=ref_documento]").val(data.TidCodi);
  $("[name=ref_serie]").focus();
  show_modal("hide", modal_tipodocumento);
}

function enter_table_ele_click() {
  let modal = $(".modal.fade.in");
  enter_table_ele(modal)
}

/**
 * 
 * @param {Json} data 
 */
function error_buscar_producto(data) {
  console.log("error_buscar_producto", this, data);
  show_modal("show", "#modalSelectProducto");
  table_productos.search(this.id_busqueda).draw();

}


function buscar_producto(id, tr = null) {

  if (tr) {
    poner_data_producto(tr.data('info'))
  }

  else {
    let data = {
      codigo: id,
    };

    let thisData = {
      id_busqueda: id
    }

    let funcs = {
      success: poner_data_producto,
      error: error_buscar_producto.bind(thisData)
    };
    ajaxs(data, url_buscar_producto_datos, funcs);
  }
}


function enter_table_ele(modal) {

  // console.log("enter_accion",enter_accion);

  if (enter_accion) {
    $(".modal-backdrop").remove()
    return;
  }
  enter_accion = true;

  let trSelect = $("table tr.select", modal);

  if (trSelect.length) {

    if (modal.is("#modalSelectCliente")) {
      let data = {
        codigo: trSelect.find("td:eq(0)").text(),
        tipo_documento: trSelect.find("td:eq(1)").text(),
      };
      let funcs = {
        success: poner_data_cliente,
      };
      ajaxs(data, url_buscar_cliente, funcs);
    }

    else if (modal.is("#modalNuevoCliente")) {
      document.getElementById("nuevocliente").focus();
    }

    else if (modal.is("#modalSelectProducto")) {

      buscar_producto(trSelect.find("td:eq(0)").text(), trSelect);

    }

    else if (modal.is("#modalSelectTipoDocumentoPago")) {


      let data = {
        codigo: trSelect.find("td:eq(0)").text(),
      };
      let funcs = {
        success: tipodocumento_selected,
      };
      ajaxs(data, url_buscar_tipo_documento, funcs);
    }

    else if (modal.is("#modalSelectCotizacion")) {
      let codigo = trSelect.find("td:eq(0)").text().split("-");
      $("[name=serie_doc]").val(codigo[0]);
      $("[name=num_doc]").val(codigo[1]);
      show_modal("hide", "#modalSelectCotizacion");
    }


    else if (modal.is("#modalSelectFactura")) {
      let fecha = trSelect.find("td:eq(2)").text();
      let numero = trSelect.find("td:eq(1)").text();
      $("[name=ref_fecha]").val(fecha);
      $("[name=ref_numero]").val(numero);
      show_modal("hide", "#modalSelectFactura");
      $("[name=ref_tipo]").focus();
    }
  }
}

function nextFocus(elemento, e = false) {
  let nextInputNameOrFunc = focus_orden[elemento];
  if (typeof nextInputNameOrFunc == "string") {
    $("[name=" + nextInputNameOrFunc + "]")
      .focus()
      .select();
    return;
  }
  nextInputNameOrFunc();
}

function setClienteDataAndNextInput(data) {
  let funcs = {
    'tipo_documento_c': poner_tipo_cliente,
  }

  poner_data_inputs(data, funcs);
  nextFocus("cliente_documento");
}

function quitar_unidad() {
  $("[name=producto_unidad]").empty();
}

function cleanInputsGroup(grupo = 'cliente', other_code = false) {
  let grupo_inputs = {
    cliente: $(".inputs_cliente"),
    producto: $(".inputs_producto"),
  }

  grupo_inputs[grupo].val("");
  setDefaultOptions(grupo_inputs[grupo]);
  other_code ? other_code() : null;
}

function setDefaultOptions(ele) {
  ele.each(function () {
    if ($(this).is("[data-default]")) {
      let default_value = $(this).data('default');

      if (this.nodeName == "INPUT") {
        $(this).val(default_value)
      }
      else {
        $('option[value=' + default_value + ']', this).prop('selected', true);
      }
    }
  })
}


function activateOrDesactivateSection(seccion) {
  let secc = {
    calculadora: 'banco',
    banco: 'calculadora',
  };

  let div_seccion = $("." + seccion);
  let div_seccion_opuesta = $("." + secc[seccion]);

  div_seccion
    .removeClass('inactive');

  div_seccion_opuesta
    .addClass('inactive')

  div_seccion.find("select,input").not('.disabledFijo').each(function (index, dom) {

    console.log("activar estos elementos", $(dom));
    $(dom).prop('disabled', false);

  });

  div_seccion_opuesta.find("select,input").not('.disabledFijo').each(function (index, dom) {

    $(dom).prop('disabled', true);
  });

}



function error_buscando_cliente(data) {
  cleanInputsGroup();
  show_modal("show", "#modalNuevoCliente")
  document.getElementById("nuevocliente").focus();
  document.getElementById("nuevocliente").focus();
  let btn_nuevo_cliente = $("#nuevocliente");

  let documento_numero = $("[name=cliente_documento]").val();
  let href = btn_nuevo_cliente.data('url').replace('XXX', documento_numero)
  btn_nuevo_cliente.attr('href', href);
}

function accionar_buscar_cliente(e) {
  let cliente_documento = $("[name=cliente_documento]").val();
  // console.log("evento",e);

  if (e.keyCode === 13 || e.type == "blur") {

    if (cliente_documento.trim().length) {
      if (!isNaN(Number(cliente_documento)) || cliente_documento == ".") {

        let data = {
          codigo: cliente_documento,
        };

        let funcs = {
          success: setClienteDataAndNextInput,
          error: error_buscando_cliente
        };

        ajaxs(data, url_verificar_cliente, funcs);
      }
    }

    else {
      cleanInputsGroup();
    }
  }
}


function calcular_porcentaje(e = false) {
  console.log("calculando porcentaje", e);
  if (e) {
    if (e.keyCode === 13) return false;
  }

  console.log("calculando porcentaje");
  let porcentaje = Number($("[name=producto_percepcion]").val());
  let producto_percepcion = $("[name=producto_percepcion_importe]");
  let value_importe = Number($("[name=producto_importe]").val());
  let total = 0;

  producto_percepcion.val("0");

  if (validateNumber(value_importe) && validateNumber(porcentaje)) {
    total = fixedValue((value_importe / 100) * porcentaje)
    producto_percepcion.val(total);
  }
  return total;
}

function calcular_igv() {
  let igv = $("[name=producto_igv]");
  let igv_total = $("[name=producto_igv_total]");
  let value_igv = Number(igv.find("option:selected").attr("data-porc"));
  let value_importe = Number($("[name=producto_importe]").val());

  $("[data-namedb=proigvv]").val(igv.find("option:selected").data('value'));

  if (validateNumber(value_importe)) {

    let importe_resto = value_igv ?

      fixedValue(value_importe / value_igv) : value_igv;

    let resultado = importe_resto ? fixedValue(value_importe - importe_resto) : importe_resto;

    igv_total.val(resultado);
  }

  else {
    igv_total.val("");
  }
}

// original
// function accionar_buscar_producto(e)
// {
//   if( e.keyCode === 13 || e.keyCode === undefined ){
//     let producto_name = 
//     $(this).is('[name=producto_nombre]') ?      
//       $('[name=producto_nombre]').val() :
//       $('[name=producto_codigo]').val();

//     if($(this).is('[name=producto_nombre]')){

//     }


//     show_modal( "show" , "#modalSelectProducto");
//     table_productos.search( producto_name ).draw();  
//   }

//   // e.stopPropagation();
// }

// serch pro wraning
function accionar_buscar_producto(e) {
  // e.keyCode === 13 enter
  // e.keyCode === 9 tab
  // console.log("adsasd");

  let keyPresed = e.keyCode;
  let $input = $(this);
  let value_input = $input.val().trim();
  let $isInputCode = $input.is('[name=producto_codigo]');

  $isInputCode ?
    $(".select-field-producto").find("option[value=codigo]").prop('selected', true) :
    $(".select-field-producto").find("option[value=nombre]").prop('selected', true);

  if (!(keyPresed === 13 || keyPresed === 9)) {
    return;
  }

  console.log("keyPress", keyPresed, value_input);
  // Si es el codigo input del codigo de producto
  if ($isInputCode) {

    if (keyPresed == 13) {
      if (value_input) {
        buscar();
      }
      else {
        show_modal("show", "#modalSelectProducto");
        table_productos.search(value_input).draw();
      }
    }
  }

  // El input de busqueda
  else {
    if (keyPresed == 13) {

      let valueInputCode = $("[name=producto_codigo]").val().trim();

      if (valueInputCode) {
        $("[name=producto_unidad]").focus();
      }
      else {
        show_modal("show", "#modalSelectProducto");
        table_productos.search(value_input).draw();
      }
    }
  }
}


function importe() {
  return fixedValue(
    $("[name=producto_cantidad]").val() * $("[name=producto_precio]").val()
  );
}

function calcular_importe() {
  $("[name=producto_importe]").val(fixedValue(importe()));
}

function fixedValue(value) {
  if (value == undefined || typeof value == "object") {
    return "0.00";
  }

  return Number(value).toFixed(2);
}

function descuento() {
  let descuento_value = Number($("[name=producto_dct]").val());
  return (importe() / 100) * descuento_value;
}

function calcular_descuento(e) {
  if (e) {
    if (e.keyCode === 13) return false;
  }
  let $importe = $("[name=producto_importe]");

  $importe.val(fixedValue(importe() - descuento()));
}

function moneda_precio_change() {
  set_precio();
}

function teclado_acciones(e) {
  // 40 => [hacia abajo]
  // 38 => [hacia arriba]
  // 30 => [enter]
  let keyCode = e.keyCode;
  let modalUp = modales_select.filter('.in');
  let modalIsOpen = modales_select.is(':visible');

  // Subir o bajar
  if (keyCode === 40 || keyCode === 38) {
    if (modalIsOpen) {
      subir_bajar_table(keyCode, modalUp);
      return false; // loekff_4458 
    }
  }

  // Enter
  else if (keyCode === 13) {
    if (modalIsOpen) {
      enter_table_ele(modalUp);
      return false; // loekff_4458
    }
  }
}

function cambiar_focos(e) {
  if (e.keyCode == 13) {
    let name_elemento = $(this).attr('name');
    nextFocus(name_elemento, e);
  }
}

function action_i(action = false) {
  if (action) {
    action_item = action;
  }
  return action_item;
}

function select_item() {
  let $this = $(this);
  let tr_selec = $this.parents("tbody").find(".seleccionando");

  // console.log( "tr_select" , tr_selec.length );

  let data = false;
  cleanInputsGroup("producto");

  if (tr_selec.length) {
    // console.log( 'is' , $this.is(tr_selec) );
    if ($this.is(tr_selec)) {
      $this.removeClass('seleccionando');
      habilitarDesactivarSelect("eliminar_item", true);
      action_i("create");
    }

    else {
      tr_selec.removeClass('seleccionando');
      data = JSON.parse($this.attr('data-info'));
      $this.addClass("seleccionando");
      habilitarDesactivarSelect("eliminar_item", false);
    }
  }

  else {
    $this.addClass('seleccionando');
    habilitarDesactivarSelect("eliminar_item", false);
    data = JSON.parse($this.attr('data-info'));
  }
  // 

  if (data) {
    action_i("edit");
    current_product_data = data;
    console.log("currentdata", current_product_data)
    let unidades = { unidades: JSON.parse($this.attr('data-unidades')) };
    poner_unidades(unidades);
    data.DetCant = data.DetCant ? data.DetCant : data.Detcant;
    poner_data_inputs(data, true, null, 'data-name_item');
    console.log("Loremp ipsum odlor Poker Strategy Margy");
    // ** \\ // ** \\ ** // \\
  }

}

function modificar_item(e) {
  e.preventDefault();
  let seleccionar = select_item.bind($(this).parents('tr')[0]);
  seleccionar();
}

function eliminar_item(e) {
  console.log("eliminar item");
  e.preventDefault();
  if (confirm("Esta seguro que desea eliminar este item?")) {
    $(this).parents('tr').hide(1000, function () {
      $(this).remove();
      if (action_i() != "create") {
        cleanInputsGroup("producto", quitar_unidad);
      }
      poner_totales_cant();
      action_i('create');
    });
  }
}

function show_comment_div(e) {
  e.preventDefault();
  let div_com = $(".div_comentario");
  let div_reg = $(".div_regular");

  div_com.toggle();
  div_reg.toggle();

  if (div_com.is(':visible')) {
    div_com.find("input").focus();
  }
}

function verificar_data_factura() {
  
  $(".has-error").removeClass('has-error');

  if (!$("#table-items tbody tr").length) {
    notiYFocus("producto_nombre", "Tiene que introducir al menos un producto");
    return;
  }

  if ($("#cliente_documento").val() == "undefined" || $("#cliente_documento").val() == undefined) {
    if (accion == "create") {
      $('#cliente_documento').select2('open');
      notiYFocus("cliente_documento", "Tiene que seleccionar un cliente");
      return;
    }
  }

  if (is_ingreso()) {

    if (!$("[name=GuiSeri]").val().length) {
      notiYFocus("GuiSeri", "Tiene que introducir la serie de la guia");
      return;
    }

    if (!$("[name=GuiNumee]").val().length) {
      notiYFocus("GuiNumee", "Tiene que introducir la número de la guia");
      return;
    }
  }

  confirm_guardado()
}



function error_guardar_factura(data) {
  console.log("error al guardar la factura", data);
  $(".div_esperando").hide();
  $(".div_guardar").show();
  defaultErrorAjaxFunc(data);
}

function modal_guardar() {
  show_modal("hide", "#modalDeudas")
  show_modal("show", "#modalGuardarFactura", "static")
}

function redirectToRoute(data) {
  // console.log("redireccion redirectToRoute", data);
  // console.log("redirect" ,data);
  // return;

  location.href = data.route_redirect ? data.route_redirect : $(" .link-redirect").attr('href');
}

function confirm_guardado() {
  console.log("confirm_guardado");
  $("#modalConfirmacion").modal();
}

function aceptar_guardado(despachar = false) {
  $("#load_screen").show();

  let items = [];

  $("#table-items tbody tr").each(function (i, d) {
    let info = JSON.parse($(this).attr('data-info'));
    info['DetCant'] = info.DetCant || info.Detcant;
    items.push(info);
  });

  let data = {
    despachar: despachar,
    codigo_venta: $("[name=codigo_venta]").val(),
    tipo_documento: $("[name=tipo_documento]").val(),
    serie_documento: $("[name=serie_documento]").val(),
    nro_documento: $(".nro_documento").text(),
    cliente_documento: $("[name=cliente_documento]").val(),
    cliente_nombre: $("[name=cliente_nombre]").val(),
    observacion: $("[name=observacion]").val(),
    moneda: $("[name=moneda]").val(),
    tipo_cambio: $("[name=tipo_cambio]").val(),
    forma_pago: $("[name=forma_pago]").val(),
    fecha_emision: $("[name=fecha_emision]").val(),
    fecha_vencimiento: $("[name=fecha_referencia]").val(),
    Vtabase: $('[data-name=gravadas]').val(),
    VtaDcto: $('[data-name=descuento]').val(),
    VtaInaf: $('[data-name=inafectas]').val(),
    VtaExon: $('[data-name=exoneradas]').val(),
    VtaGrat: $('[data-name=gratuitas]').val(),
    VtaISC: $('[data-name=isc]').val(),
    VtaIGVV: $('[data-name=igv]').val(),
    id_almacen: $('[name=id_almacen]').val(),
    total_cantidad: $("[name=cantidad_total]").val(),
    total_peso: $("[name=peso_total]").val(),
    total_importe: $("[name=total_importe]").val(),
    vendedor: $("[name=vendedor]").val(),
    nro_pedido: $("[name=nro_pedido]").val(),
    doc_ref: $("[name=doc_ref]").val(),
    ref_documento: $("[name=ref_documento]").val(),
    ref_serie: $("[name=ref_serie]").val(),
    ref_numero: $("[name=ref_numero]").val(),
    ref_fecha: $("[name=ref_fecha]").val(),
    ref_motivo: $("[name=ref_motivo]").val(),
    GuiSeri: $("[name=GuiSeri]").val(),
    GuiNumee: $("[name=GuiNumee]").val(),
    ref_tipo: $("[name=ref_tipo]").val(),
    id_tipo_movimiento: $("[name=id_tipo_movimiento]").val(),
    tipo_guardado: $("[name=tipo_guardado]:checked").val(),
    items: items,
  }

  $("tr.seleccionando").each(function (index, dom) {
    let item_data = JSON.parse($(this).attr('data-info'));
    data.items.push(item_data);
  });

  let funcs = {
    success: redirectToRoute,
    complete: function () {
      $("#load_screen").hide();
    },
    error: function (data) {
      if (data.responseJSON) {

        let errors = data.responseJSON.errors;
        let mensaje = data.responseJSON.message;

        let erros_arr = [];
        for (prop in errors) {
          for (let i = 0; i < errors[prop].length; i++) {
            erros_arr.push(errors[prop][i]);
          }
        }

        notificaciones(erros_arr, 'error', mensaje)
      }

      else {
        notificaciones(data.statusText, 'error', "")
      }
    }
    // compelte : window.
  }

  tipo_guardado = $("[name=tipo_guardado]:checked").val();

  $(".div_guardar").hide();
  $(".div_esperando").show();
  ajaxs(data, url_guia_salida, funcs);
}

function show_hide_adicional_info() {
  $(".info_adicional").toggle();
}


function desactivarArea() {
  let option = $("[data-name=TipPago] option:selected");
  console.log(option.data('is_efectivo'));

  option.data('is_efectivo') ? activateOrDesactivateSection("calculadora") : activateOrDesactivateSection("banco");
}

function calculadora_resultado(cantidad, importe) {

  let estado_pago = $("#estado_pago");

  console.log("cantidad, importe", cantidad, importe);

  estado_pago.removeAttr('class');
  if (cantidad > importe) {
    estado_pago
      .addClass('VUELTO')
      .text("VUELTO");
  }
  else if (cantidad < importe) {
    estado_pago
      .addClass('SALDO')
      .text("SALDO");
  }
  else {
    estado_pago
      .addClass('COMPLETE')
      .text("COMPLETE");
  }
}

function calculadora_actions() {
  console.log("calculadora actions");
  let soles = $("[name=soles]", ".calculadora");
  let soles_value = Number(soles.val());

  let dolar = $("[name=dolar]", ".calculadora");
  let dolar_value = Number(dolar.val());

  let t_cambio = $("[name=VtaTcam]", ".calculadora");
  let t_cambio_value = Number(t_cambio.val());

  let t_recibido = $("[name=totalRecibido]", ".calculadora");
  let t_recibido_value = Number(t_recibido.val());

  let t_operacion = $("[name=totalOperacion]");
  let t_operacion_value = Number(t_operacion.val());

  let importe = Number($("[data-db=VtaImpo]").val());

  if (validateIsNotNumber(soles_value) || validateIsNotNumber(dolar_value) ||
    validateIsNotNumber(t_cambio_value)) {
    t_recibido.val(0);
    t_operacion.val(0)
    return;
  }
  else {
    let dolar_operacion = (dolar_value * t_cambio_value);
    let total = soles_value + dolar_operacion;

    t_recibido.val(total);
    t_operacion.val(importe - total);

    calculadora_resultado(total, importe);
  }

}

function pagar_factura() {

  let importe = $("[name=VtaImpo]").val()

  if (validateIsNotNumber(importe)) {
    notiYFocus("VtaImpo", "El importe tiene que ser un numero")
    return;
  }

  if (!$(".banco").is('inactive')) {

    if ($("[name=fechaPago]").val().length == 0) {
      notiYFocus("fechaPago", "La fecha de pago es necesaria")
      return;
    }
    if ($("[name=fechaVen]").val().length == 0) {
      notiYFocus("fechaVen", "La fecha vencimiento es necesaria")
      return;
    }
  }

  let data = {
    PagOper: $("[data-db=PagOper]").val(),
    VtaOper: $("[data-db=VtaOper]").val(),
    VtaImpo: $("[name=imp]").val(),
    BanCodi: $("[name=BanCodi]").val(),
    TpgCodi: $("[data-name=TipPago]").val(),
    VtaFVta: $("[data-db=fechaPago]").val(),
    VtaFVen: $("[data-db=fechaVen]").val(),
    VtaNume: $("[data-db=VtaNume]").val(),
    CuenCodi: $("[data-db=CuenCodi]").val(),
    NumOper: $("[data-db=NumOper]").val(),
    NumDoc: $("[data-db=NumDoc]").val(),
    fechaPago: $("[data-db=fechaPago]").val(),
    fechaVen: $("[data-db=fechaVen]").val(),

  };

  console.log(" Pagado el importe: create ", create);

  let funcs = {
    success: create ? guiaSalida : successPay,
  };

  ajaxs(data, url_save_pago, funcs);
}


function successPay(data) {
  show_modal("hide", "#modalPago");
  setValuetoPay(data.por_pagar);

  let tbody = $("#table_pagos tbody");
  let tr = $("<tr></tr>");

  let fecha = tdCreate(data.pago.PagFech, false, "fecha");
  let id_pago = tdCreate(data.pago.PagOper, false, "id_pago");
  let tpago = tdCreate(data.pago.TpgCodi, false, "TpgCodi");
  let moneda = tdCreate(data.pago.moneda_abbre, false, "monabre");
  let PagTCam = tdCreate(data.pago.PagTCam, false, "PagTCam");
  let importe = tdCreate(data.pago.PagImpo, false, "PagImpo");
  tr.append(fecha, id_pago, tpago, moneda, PagTCam, importe);
  tbody.append(tr);
  notificaciones("Pago registrado exitosamente", "success");
}



function salir_guia() {
  show_modal("hide", "#modalGuiaSalida");
  index_page()
}

function go_listado(tiempo = 0) {
  setTimeout(function () {
    index_page()
  }, tiempo)
}


function headerAjax() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
}

function initialFocus() {
  $("[name=tipo_documento]").focus();
}

function seleccionar_elemento(tr) {
  let table = tr.parents("table");
  let tr_select = table.find("tr.select");

  if (tr_select.length) {
    tr_select.removeClass("select");
  }

  tr.addClass("select");
}

function seleccion_elemento() {
  // console.log("seleccion de elemento", this);
  let tr = $(this);
  if (tr.find(".dataTables_empty").length == 0 || create == 0) {
    seleccionar_elemento(tr);
  }
}

function salir_alert(e) {

  e.preventDefault();

  if (confirm("Esta seguro de salir")) {
    go_listado();
  }

}

function accion_item(e) {
  e.preventDefault();
  let $t = $(this);
  // console.log("$t" , $t);
  // Crear
  if ($t.is('.crear')) {
    agregar_item();
  }

  // Modificar
  else if ($t.is('.modificar')) {
    console.log("modificar");
  }
  else {
    console.log("eli")
  }

}



function calculate_dcto_global() {
  let resp = 0;
  let dcto_gl = $("[name=descuento_global]").val();
  let totales = sum_cant();

  if (validateNumber(dcto_gl) && $("[data-name=total_importe]").val() != "0") {

    let total_venta = Number(totales.gravadas) + Number(totales.exoneradas) + Number(totales.inafectas);

    console.log("total venta, descuento y dcto valor", total_venta, (total_venta / 100 * dcto_gl));

    return (total_venta / 100 * dcto_gl)

  }

  return resp;
}

function calculate_descuento(value, dcto) {
  let elementos = [
    $("[data-name=descuento]"),
    $("[data-name=gravadas]"),
    $("[data-name=inafectas]"),
    $("[data-name=exoneradas]"),
    $("[data-name=igv]"),
    $("[data-name=total_documento]"),
    $("[data-name=total_importe]"),
  ];

  for (var i = 0; i < elementos.length; i++) {

    let ele = elementos[i];
    let eleValue = Number(ele.val());
    let res;

    if (ele.is('[data-name=descuento]')) {
      // console.log("poner el descuento " , eleValue , value );
      res = eleValue + value;
    }
    else {
      res = eleValue ? eleValue - ((eleValue / 100) * dcto) : "0.00";
    }

    console.log("res desc", res);

    ele.val(fixedValue(res));
    cantidad_letras($("[data-name=total_importe]").val());
  }

}

function descuento_global(e) {
  let valid_number = false;
  let $t = $("[name=descuento_global]");
  let value = $t.val();
  poner_totales_cant();

  if (validateNumber(value) && value != "0") {

    let descuento = calculate_dcto_global();
    calculate_descuento(descuento, value);
  }
}

function ver_data_cliente(e) {
  e.preventDefault();
  let target = $(this).data('element');
  console.log("target", target);

  // console.log("ver data cliente ");
  // let ele = $(".row-cliente-adicional")
  let ele = $("[data-target=" + target + "]");
  if (ele.is('.hide')) {
    ele.removeClass('hide')
  }
  else {
    ele.addClass('hide')
  }
}

function mostrar_productos(data) {
  let table = $("#datatable-producto-cliente");
  table.find("tbody").empty();
  for (var i = 0; i < data.length; i++) {
    let tr = $("<tr></tr>");
    let tds = [
      tdCreate(data[i].Linea, false, 'Linea'),
      tdCreate(data[i].DetNomb, false, 'Nombre'),
      tdCreate(data[i].MarNomb, false, 'Marca'),
      tdCreate(data[i].DetUnid, false, 'DetUnid'),
      tdCreate(data[i].DetCant, false, 'Linea'),
      tdCreate(data[i].Estado, false, 'Moneda'),
      tdCreate(data[i].DetPrec, false, 'Precio'),
      tdCreate(data[i].DetImpo, false, 'Importe'),
      tdCreate("01", false, 'Tdoc'),
      tdCreate(data[i].nro_documento, false, 'NroDocumento'),
      tdCreate(data[i].fecha, false, 'Fecha'),
    ]

    // var camposFiltados = "TieCodi,ItenNum,UniCodi,Linea,Nombre,Marca,Moneda,NroDocumento,Fecha".split(",");

    tr.append(tds);
    table.find("tbody").append(tr);
  }


}

function show_articulo_vendidos() {

  // id_factura : $("[name=codigo_venta]").val()

  if (!$("[name=cliente_documento]").val().length) {
    $("[name=cliente_documento]").focus();
    notificaciones("Tiene que seleccionar un cliente primero", "danger");
    return;
  }

  let data = {
    id_cliente: $("[name=cliente_documento]").val(),
  }

  let funcs = {
    success: mostrar_productos
  }

  ajaxs(data, url_productos_vendidos, funcs)

  // console.log("mostrar articulos vendidos");
  show_modal("show", "#modalProductosVendidos")
}

function show_modal_importaciones() {
  show_modal("show", "#modalImportacion", "static");

  $("[name=serie_doc]").focus()
}


function aceptar_importacion() {

  let serie_val = $("[name=serie_doc]").val();
  let num_val = $("[name=num_doc]").val();

  // console.log("ver si ay algo de ", serie_val, num_val );

  if (!serie_val.length) {
    notificaciones("Tiene que escribir la serie", "danger");
    $("[name=serie_doc]").focus()
    return;
  }
  if (!num_val.length) {
    notificaciones("Tiene que escribir el numero del documento", "danger");
    $("[name=num_doc]").focus()
    return;
  }


  let data = {
    serie_documento: serie_val,
    numero_documento: num_val,
  };

  let funcs = {
    success: procesarImportacion
  }

  ajaxs(data, url_consulta_cotizacion, funcs);
}

function procesarImportacion(data) {
  let items = data.items;
  let table = data.table;

  for (let i = 0; i < items.length; i++) {
    add_item(items[i]);
  }

  poner_data_inputs(data.cliente);

  show_modal("hide", "#modalImportacion");

  notificaciones("Importación exitosa", "success");
  $("[name=serie_doc]").val("");
  $("[name=num_doc]").val("");
}

function mostrar_cotizac() {
  show_modal("show", "#modalSelectCotizacion");
}

function mostrar_condi_venta() {
  // console.log("condicion, venta");
  show_modal("show", "#modalCondicionVenta");
}

function guardar_condicion() {
  let data = {
    descripcion: $("[name=condicion_venta]").val()
  };
  let funcs = {
    success: function () {
      show_modal("hide", "#modalCondicionVenta");
    }
  }
  ajaxs(data, url_guardar_condicion, funcs);
  // console.log("url_guardar_condicion", url_guardar_condicion)
}


function activar_desactivar_email(activar = true) {
  let resp = activar ? "activar" : "desactivar";
  let boton_email = $(".enviar_mail");

  let estados = {
    'activar': {
      'title': 'Enviar Email',
      'icono': 'fa fa-envelope',
      'clas_remove': 'disabled',
      'clas_active': '',

    },
    'desactivar': {
      'title': 'Enviando Email',
      'icono': 'fa fa-spin fa-spinner',
      'class_remove': '',
      'clas_active': 'disabled',
    }
  }

  let info = estados[resp];

  boton_email
    .removeClass(info.clas_remove)
    .addClass(info.clas_active);

  boton_email.attr('title', info.title);
  boton_email.find('span')
    .attr("class", "")
    .addClass(info.icono);

}

function envio_exitoso(data) {
  activar_desactivar_email(true);
  notificaciones("Corre enviado exitosamente", "success");
  console.log("Corre enviado exitosamente", data);
}

function confirm_enviar_mail() {
  if (confirm("Esta seguro de enviar por correo el documento")) {
    console.log("enviar");

    activar_desactivar_email(false);

    let data = {
      'id_factura': $("[name=codigo_venta]").val(),
    };

    let funcs = {
      success: envio_exitoso,
      error: function (data) {
        console.log("error, ", data)
        activar_desactivar_email(true);
        notificaciones("Su correo no pude enviase", "error");

      },
    };

    ajaxs(data, url_enviar_email, funcs);

  }
}

function ver_pagos() {
  console.log("ver pagos");
  show_modal('show', "#modalPagos")
  select_first_ele($("#table_pagos"));
}


function getTrSelect() {
  let tr_select = $("#modalPagos .select");
  if (tr_select.length) {
    tr_pago.ele = tr_select;
    tr_pago.id_pago = tr_select.find("[data-campo=id_pago]").text();
  }

  return tr_select.length;
}

function ejecutar_accion() {
  if ($(this).is('.nuevo_pago')) {
    prepare_nuevo_pago();
  }

  else {

    if (getTrSelect()) {

      if ($(this).is('.ver_pago_select')) {
        prepare_verpago();
      }
      else {
        quitar_pago();
      }
    }

  }
}


function setValuetoPay(value) {
  $(".cantidad_pagar").text(value);
}


function borradoExitosoPago(nuevoAPagar) {
  setValuetoPay(nuevoAPagar);
  tr_pago.ele.remove();
  tr_pago = {};
  notificaciones("Pago eliminado exitosamente", "success");
  select_first_ele($("#table_pagos"));
}


function prepare_nuevo_pago() {
  let data = {
    id_factura: $("[name=codigo_venta]").val()
  }
  let funcs = {
    success: preparar_modal_pago,
  }

  ajaxs(data, url_check_pago, funcs);
}

function poner_data_pago(data) {
  console.log("Data", data);

  poner_data_inputs(data, false, null, 'data-pago');
}

function prepare_verpago() {
  let data = {
    id_pago: tr_pago.id_pago
  }

  let funcs = {
    success: poner_data_pago
  };

  ajaxs(data, url_data_pago, funcs)

  show_modal("show", "#modalPago");
  console.log("ver pago");
}

function quitar_pago() {

  if (confirm("Esta seguro que desea eliminar este pago?")) {
    let data = {
      id_pago: tr_pago.id_pago,
    }

    let funcs = {
      success: borradoExitosoPago
    }

    console.log("quitar pago");
    ajaxs(data, url_quitar_pago, funcs);

  }
}

function anulacion_exitosa(data) {
  notificaciones(data.message, "success");
  $(".ticket_value").text(data.message).show();
}

// function anular_boleta_exitosa(data)
// {
//   notificaciones("Boleta anulada correctamente" , "success");    
//   $(".ticket_value").text("Anulado").show();        
//   $(".block_elemento").hide();
//   $(".anular_documento").attr('disabled');
// }

function anular_documento() {
  if (confirm("Desea Anular este documento")) {

    $(".block_elemento").show();

    let data = {
      id_factura: $("[name=nro_oper]").val()
    }

    let funcs = {
      success: anulacion_exitosa
    }

    ajaxs(data, url_anular_documento, funcs);
  }

}


function verificacion_exitosa(data) {
  notificaciones("ANULADO SUNAT(0)", "success");
  $(".block_elemento").hide();
  $(".anular_documento").addClass('disabled');
  console.log("verificacion_Exitsa", data);
}


function verificar_ticket(preguntar) {
  ejecutar = true;

  if (!(typeof preguntar == "boolean")) {

    ejecutar = confirm("Desea validar el ticket?")
  }

  if (ejecutar) {

    let data = {
      id_factura: $("[name=codigo_venta]").val()
    }

    let funcs = {
      success: verificacion_exitosa
    }

    ajaxs(data, url_verificar_ticket, funcs);
  }

}

function quitar_estilos() {
  $("#datatable-factura_select").removeAttr('style');
}

// function buscar()
// {
//   console.log("buscar producto");
//   let id = $("[name=producto_codigo]").val();
//   if( isNaN(id) || !id.trim().length ){
//     return;
//   }
//   buscar_producto( $("[name=producto_codigo]").val() );
// }

function buscar() {
  let id = $("[name=producto_codigo]").val();

  if (!id.trim().length) {
    return;
  }
  buscar_producto(id);
}

function salir_pago_accion() {
  if (create) {
    guiaSalida();
  }
  else {
    show_modal("hide", "#modalGuiaSalida");
  }
}


function select2ubigeo_init() {
  let $ubigeo = $('#ubigeo');
  // @TODO
  $ubigeo.select2({
    placeholder: "Buscar Ubigeo",
    minimumInputLength: 2,
    dropdownParent: $('#modalDespacho'),
    ajax: {
      url: url_ubigeo,
      dataType: 'json',
      data: function (params) {
        // console.log( "params" , params );
        return {
          data: $.trim(params.term)
        };
      },
      processResults: function (data) {
        console.log("processResultsc", data);
        return {
          results: data
        };
      },
      cache: true
    }
  });

  if ($ubigeo.data('info')) {

    let data_ubigeo = $ubigeo.data('info');

    var data = {
      id: data_ubigeo.ubicodi,
      text: data_ubigeo.ubicodi + ' (' + data_ubigeo.ubinomb + ')'
    };

    console.log("data_ubigeo", data_ubigeo, data);

    var newOption = new Option(data.text, data.id, false, false);
    $ubigeo.append(newOption).trigger('change');
  }

}


function select2_init() {
  let $cliente = $('#cliente_documento');
  if ($cliente.data('search')) {
    $cliente.select2({
      placeholder: "Buscar cliente",
      minimumInputLength: 0,
      dropdownParent: $('#modalDespacho'),
      ajax: {
        url: url_buscar_cliente_select2,
        dataType: 'json',
        data: function (params) {
          return {
            data: $.trim(params.term)
          };
        },
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    if ($cliente.data('cliente')) {
      let data_cliente = $cliente.data('cliente');
      let data_tipo_documento = $cliente.data('tipo_documento');
      var data = {
        id: data_cliente.PCCodi,
        text: data_cliente.PCRucc + '-' + data_cliente.PCNomb
      };
      $("[name=tipo_documento_c]").val(data_tipo_documento.TdocNomb);
      var newOption = new Option(data.text, data.id, false, false);
      $cliente.append(newOption).trigger('change');
    }
  }
}

function open_modal_cliente() {
  $("#modalCliente").modal();
}


function send_sunat() {
  if (confirm("Desea mandar a la sunat")) {

    let data = {
      id_guia: $("[name=nro_oper]").val()
    };

    $(".block_elemento").show();

    let funcs = {
      success: function (data) {
        notificaciones(data.message, "success");
      },
      complete: function (data) {
        activar_button("#sendsunat")
        $(".block_elemento").hide();
        setTimeout(() => {
          index_page()
        }, 2000);


      }

    };

    $(".block_elemento").show();
    desactivar_button("#sendsunat")
    ajaxs(data, url_guia_sendsunat, funcs);
  }
  else {
    setTimeout(function () {
      index_page();
    }, 500);
  }
}

function reload() {
  location.reload();
}

function is_ingreso() {
  return $(".index-page").attr('data-is_ingreso') == "1";
}

function index_page() {
  location.href = $(".index-page").attr('href');
}

function send_sunat_or_reload() {
  if (is_ingreso()) {
    index_page();
  }
  else {
    send_sunat()
  }
}


function successDespacho(data = null) {
  // console.log("data", data );
  show_hide_modal("modalDespacho", false, false, function () {
    notificaciones(data.message, "success");

    if (window.imprimir) {
      window.open(data.route_impresion,
        "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=300,left=100,width=800,height=300");
    }

    send_sunat_or_reload();

  });

}




function ejecutar_despacho() {
  $("#load_screen").show();

  let data = $("#form_despacho").serialize();
  window.imprimir = $("[name=imprimir]:checked").val();
  let funcs = {
    success: successDespacho,
    complete: function () {
      $("#load_screen").hide();
    }
  };

  ajaxs(data, url_despacho, funcs)
}


function redactar() {

  console.log("popover");

  show_hide_modal("modalMail", true)
  $(".popover").hide();

  // id_guia = tr_selected.find('td:eq(0)').text().trim();

  // $(".popover").hide();
  // show_hide_modal("modalRedactarCorreo",true);    
  // console.log("redactar");
}


function change_cantidad() {
  let tr = $(this).parents('tr');
  let info = tr.data('info');
  let value = $(this).val();
  info.DetCant = value;
  tr.attr('data-info', JSON.stringify(info));
}

function validar_guia(e) {
  e.preventDefault();
  let url = $(this).data('url');
  let data = {};
  let funcs = {
    success: function (data) {
      notificaciones(data.message, "success");
      console.log("success", data);
    }
  }

  ajaxs(data, url, funcs);

}

function poner_codigo_documento() {
  let nro_codigo = $("[name=serie_documento] option:selected").attr('data-codigo');
  $(".nro_domento").text(nro_codigo);
}

/**
 * Cambiar motivo de despacho
 */
function changeMotivo() {

  let $motivo = $("[name=motivo_traslado]");
  let $old_option = $motivo.data('old_option');
  let $current_option = $motivo.find('option:selected');
  // Invertir
  $motivo.data('old_option', $current_option);

  console.log( $old_option, $current_option )

  // Si se de un tipo diferente revizar
  if ($old_option.attr('data-type') !== $current_option.attr('data-type')) {

    let $direccion_partida = $("[name=direccion_partida]");
    let $direccion_llegada = $("[name=direccion_llegada]");
    let $ubigeo_partida = $("[name=ubigeo_partida]");
    let $ubigeo_llegada = $("[name=ubigeo_llegada]");

    let direccion_llegada_val = $direccion_llegada.val();
    let direccion_partida_val = $direccion_partida.val();

    $direccion_partida.val(direccion_llegada_val);
    $direccion_llegada.val(direccion_partida_val);


    // Cambiar valores del ubigeos
    let ubigeo_llegada_data = {
      id: $ubigeo_llegada.attr('data-id'),
      text: $ubigeo_llegada.attr('data-text')
    }

    let ubigeo_partida_data = {
      id: $ubigeo_partida.attr('data-id'),
      text: $ubigeo_partida.attr('data-text')
    }

    // Ubigeo partida
    $ubigeo_partida.attr('data-id', ubigeo_llegada_data.id);
    $ubigeo_partida.attr('data-text', ubigeo_llegada_data.text);
    // Ubigeo llegada
    $ubigeo_llegada.attr('data-id', ubigeo_partida_data.id);
    $ubigeo_llegada.attr('data-text', ubigeo_partida_data.text);

    // invertir valores
    $ubigeo_partida.attr('data-id', ubigeo_llegada_data.id);
    $ubigeo_partida.attr('data-text', ubigeo_llegada_data.text);

    $ubigeo_llegada.attr('data-id', ubigeo_partida_data.id);
    $ubigeo_llegada.attr('data-text', ubigeo_partida_data.text);

    $ubigeo_partida.empty();
    $ubigeo_llegada.empty();

    $ubigeo_partida.select2('destroy');
    $ubigeo_llegada.select2('destroy');


    initSelect2('#ubigeo_partida');
    initSelect2('#ubigeo');

    $('#ubigeo_partida').trigger('change');
    $('#ubigeo').trigger('change');
  }

  // console.log({ $current_option })

  $current_option.val() == "08" || $current_option.val() == "09" ? $(".campos-export").show() : $(".campos-export").hide();

  // Cambiar al valor actual
  $motivo.data('old_option', $current_option.val()  );
}

function mostrarModalTraslado() {
  $("#modalTraslado").modal();
}

/**
 * Cambiar motivo de despacho
 */
function saveCurrentMotivo() {
  let $motivo = $("[name=motivo_traslado]");
  let $current_option = $motivo.find('option:selected');
  $motivo.data('old_option', $current_option);
}

function aceptar_guardado_guia() {
  $(this).parents("#modalTraslado").find('form').submit();
  $(".load_screen").show();
}

function crear_cliente() {
  if (table_clientes) {
    table_clientes.search(ultimo_codigo_cliente);
  }
  window.open($(this).data('url'));
}


//
function consultar_grupo_filter() {
  console.log("consultando grupo familias")

  let grupo = $("[name=grupo_filter] option:selected");
  let id_grupo = grupo.val();
  let familias = grupo.data('familias');

  let familia_empty = { famCodi: null, famNomb: '-- SELECCIONAR FAMILIA --' };

  if (!id_grupo) {
    agregarASelect([familia_empty], "familia_filter", "famCodi", "famNomb");
    table_productos.draw();
  }

  else {
    if (familias.length) {
      agregarASelect(familias, "familia_filter", "famCodi", "famNomb");
      table_productos.draw();
    }
    else {

      let data = {
        'id_grupo': id_grupo
      }

      // formData.append('id_grupo', id_grupo);
      let funcs = {
        success: function (familias) {
          if (familias.length) {
            agregarASelect(familias, "familia_filter", "famCodi", "famNomb");
            grupo.attr('data-familias', JSON.stringify(familias));
            select_value('familia_filter', null);
          }
          // recovery-stranger-things
          else {
            agregarASelect([familia_empty], "familia_filter", "famCodi", "famNomb");
          }
          table_productos.draw();
        },
      }

      ajaxs(data, $("[name=grupo_filter]").attr('data-url'), funcs);
      // ajaxs(formData, $("[name=grupo_filter]").attr('data-url'), funcs);
    }
  }
}

function showHideCampoTraslados()
{
  if($("#modalDespacho").attr('data-transportista') == "1" ){
    return;
  }

  const $this = $("[name=modalidad_traslado]");
  const tipo = $this.find('option:selected').val();
  $(".mod-campo").hide();
  $(`.mod-campo[data-field-traslado=${tipo}]`).show();
}

function completeNumber(e)
{
  // e.preventDefault();
  const $input = $(e.target);
  const value = e.target.value;
  
  if (value == "" || value.length == 6 ){
    return;
  }

  $input.val( value.padStart( e.target.getAttribute('maxlength') , 0));
}

function events()
{
  $("[name=GuiNumee],[name=GuiSeri]").on('blur', completeNumber )

  // Modalidad traslado
  $("[name=modalidad_traslado]").on('change', showHideCampoTraslados)

  $("#trasladoAlmacen").on('click', mostrarModalTraslado);

  $("[name=grupo_filter]").on('change', consultar_grupo_filter);

  $("[name=familia_filter]").on('change', () => {
    table_productos.draw()
  });

  $("body").on('change', '.select-field-producto', function (E) {
    table_productos.draw();
  });

  $("[name=DetCant]").on('keyup', change_cantidad);

  $("#validar").on('click', validar_guia);

  $("*").on('click', '.redactar', redactar);
  // modificar_item 
  $('#cliente_documento').on('select2:selecting', function (data) {
    let tDocCodi = data.params.args.data.data.tipo_documento_c.TdocNomb;
    $("[name=tipo_documento_c]").val(tDocCodi);
  });


  $("#showModalDespacho").on('click', function () {
    console.log("loremp ipsum");
    $('#modalDespacho').modal();;
    // show_hide_modal('modalDespacho' , true );
  })


  $('#cliente_documento').on('select2:close', function (data) {
    producto_input_focus
      .select()
      .focus();
  });

  $("#sendsunat").on('click', send_sunat);

  $(".save_despacho").on('click', ejecutar_despacho);

  $("[name=motivo_traslado]").on('change', changeMotivo);

  $("#newCliente").on('click', open_modal_cliente);

  $("#table-items").on('click', '.modificar_item', modificar_item);
  $("#table-items").on('click', '.eliminar_item', eliminar_item);
  $("#table-items").on('dblclick', ".tr_item", select_item);

  $(".crear_cliente").on('click', crear_cliente);

  $("[name=producto_codigo]").on('blur', buscar)

  $(".modal").on('hidden.bs.modal', function () {
    enter_accion = false;
  })

  $("#modalSelectProducto").on('shown.bs.modal', function (e) {
    let table_id = $("table", this).attr('id')
    let select = "#" + table_id + "_filter input";
    $(select).focus();
  });


  $(".modal").on('show.bs.modal', function (e) {
    if (enter_accion) { e.preventDefault(); return; }
  })

  $(".verificar_ticket").on('click', verificar_ticket);

  $(".nuevo_pago,.ver_pago_select,.remove_pago").on('click', ejecutar_accion);

  $("#salir_pago").on('click', salir_pago_accion);

  $("#modalSelectFactura").on('show.bs.modal', quitar_estilos);

  $(".pagos-button").on('click', ver_pagos);

  $(".enviar_mail").on('click', confirm_enviar_mail);

  $(".guardar_condicion").on('click', guardar_condicion);

  $(".condi_venta").on('click', mostrar_condi_venta);

  $(".lista-coti").on('click', mostrar_cotizac);

  $(".aceptar_importacion").on('click', aceptar_importacion);

  $(".importar_accion").on('click', show_modal_importaciones);

  $(".art_vendidos").on('click', show_articulo_vendidos)

  $(".open-data").on('click', ver_data_cliente)

  $("[name=descuento_global]").on('keyup', descuento_global)

  $(".seguir_operacion").on('click', modal_guardar)

  $("[name=producto_dct]").on('keyup', calcular_descuento)

  $(".buscar_cliente").on('click', buscar_cliente);

  $("#salir_").on('click', salir_alert)

  $(".item-accion").on('click', accion_item);

  table_clientes.on('draw.dt', select_tabla_clientes);
  table_productos.on('draw.dt', select_tabla_productos);
  table_cotizacion.on('draw.dt', select_tabla_cotizacion);

  $("#datatable-productos,#datatable-clientes,#table_pagos,#datatable-clientes, #datatable-factura_select").on('click', "tbody tr", seleccion_elemento);

  $("#datatable-productos,#datatable-clientes,#table_pagos-pagos,#datatable-factura_select").on('dblclick', "tbody tr", enter_table_ele_click);

  $("#pay_factura").on('click', pagar_factura);

  $(".elegir_elemento").on('click', enter_table_ele_click)

  $("*").on("keydown", teclado_acciones);

  // o
  // $("[name=cliente_documento]").on("keydown" , accionar_buscar_cliente );

  // $("[name=producto_codigo] , [name=producto_nombre]").on("keydown" , accionar_buscar_producto );
  $("[name=producto_codigo],[name=producto_nombre]").on("keydown", accionar_buscar_producto);


  // $("#boton_buscar").on("click" , accionar_buscar_producto );

  $("#boton_buscar").on("click", function (e) {
    e.preventDefault()
    show_modal("show", "#modalSelectProducto");
    let value_input = $("[name=producto_nombre]").val().trim();
    $(".select-field-producto").find("option[value=nombre]").prop('selected', true);
    table_productos.search(value_input).draw();
  });

  $("[name=producto_nombre]").on("dblclick", accionar_buscar_producto);

  // cambiar de foco carefull
  $("[name=fecha_emision],[name=fecha_referencia],[name=moneda],[name=tipo_cambio],[name=forma_pago],[name=producto_unidad],[name=producto_cantidad],[name=producto_precio],[name=ref_documento],[name=ref_serie],[name=ref_numero],[name=ref_fecha], [name=producto_isc],[name=producto_isc_other],[name=producto_percepcion],[name=producto_dct] ").on("keydown", cambiar_focos);

  $("[name=soles],[name=dolar],[name=VtaTcam]", ".calculadora").on('keyup', calculadora_actions);

  $("[data-name=TipPago]").on('change', desactivarArea);

  $(".totales .info_principal").on('click', show_hide_adicional_info);

  // $("#guardarFactura").on('click' , verificar_data_factura );

  // $("#aceptar_guardado").on('click' , aceptar_guardado );
  //     
  $(".aceptar_guia").on('click', aceptar_guardado_guia);
  $("#aceptar_guardado").on('click', confirm_guardado);
  $(".agregar_comentario").on('click', show_comment_div);  
  $(".salir_guia").on('click', salir_guia);
  $("[name=moneda]").on("change", moneda_precio_change);
  $("[name=producto_unidad]").on("change", set_precio);
  $("[name=producto_cantidad] , [name=producto_precio]").on("keyup", calcular_importe);
  $("[name=producto_dct]").on("keyup", calcular_descuento);
  $("[name=producto_igv]").on("change", calcular_igv);
  $("[name=forma_pago]").on("change", agregar_dias);
  $("[name=producto_percepcion]").on("keyup", calcular_porcentaje);
  $(".anular_documento").on("click", anular_documento);
  // tipo de documento
  $("[name=tipo_documento]").on("change", cambiar_tipo_documento);
  // serie documento
  $("[name=serie_documento]").on("change", poner_codigo_documento);
  $(".save_guia").on("click", verificar_data_factura);

  // 
  $(".acept_confirmation").on("click", function () {
    let despachar = $("[name=despachar]:checked").val();
    aceptar_guardado(despachar)
  });
  // 

  $(".index-page").on("click", salir_alert);


  function getSerieData() {
    return $("[name=gen_tdoc] option:selected").data('series');
  }


  function setCorrelativeDoc() {
    let data = getSerieData();
    let serie = $("[name=gen_serie").val();
    let nuevo_codigo;

    console.log("setDataCorrelative", data, serie)

    for (let i = 0; i < data.length; i++) {
      let ser = data[i];

      if (ser.id == serie) {
        nuevo_codigo = ser.nuevo_codigo
        break;
      }
    }

    $(".correlative-doc-generad").val(nuevo_codigo)
  }

  // MODAL PARA GENERAR DOCUMENTO A PARTIR DE LA GUIA

  $("[name=gen_tdoc").on("change", function () {

    let data = getSerieData();
    agregarASelect(data, 'gen_serie', 'id', 'id')
    setCorrelativeDoc()

  });


}

function sumStock(value, data, info) {
  // console.log(arguments)
  let sum = Number(info.prosto1) + Number(info.prosto2) + Number(info.prosto3) + Number(info.prosto4) + Number(info.prosto5) + Number(info.prosto6) + Number(info.prosto7) + Number(info.prosto8) + Number(info.prosto9) + Number(info.prosto10);
  return fixedNumber(sum);
}

function initDatatables() {
  table_cotizacion = $('#datatable-cotizacion_select').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "ordering": false,
    "ajax": url_buscar_cotizaciones,
    "oLanguage": { "sSearch": "", "sLengthMenu": "_MENU_" },
    "initComplete": function initComplete(settings, json) {
      $('div.dataTables_filter input').attr('placeholder', 'Buscar cotización');
    },
    "columns": [
      { data: 'CotNume' },
      { data: 'CotFVta' },
      { data: 'cliente.PCRucc' },
      { data: 'cliente.PCNomb' },
      { data: 'moneda.monabre' },
      { data: 'usuario.usulogi' },
    ]
  });


  $("#datatable-productos").one("preInit.dt", function () {
    $button = $("<select class='select-field-producto input-sm form-control'><option value='codigo'>Codigo</option> <option value='nombre'>Nombre</option> </select>");
    $("#datatable-productos_filter label").prepend($button);
    $button.button();
  });


  function fixedNumber(v, codigo = false) {
    if (isNaN(v)) {
      return v;
    }
    return codigo ? v : (Math.round(v * 100) / 100).toFixed(2);
  }


  let columns = [
    { data: 'ProCodi', searchable: false },
    { data: 'unpcodi', searchable: false },
    { data: 'ProNomb', className: 'nombre_producto', searchable: false },
    { data: 'marca_.MarNomb', searchable: false },
    { data: 'ProPUCD', 'className': 'text-right', searchable: false },
    { data: 'ProPUCS', 'className': 'text-right', searchable: false },
    { data: 'ProMarg', 'className': 'text-right', searchable: false },
    { data: 'ProPUVS', 'className': 'text-right', searchable: false },
    { data: 'ProPUVS', 'className': 'text-right', searchable: false, render: sumStock },
  ];

  let cantidad_almacenes = $("#datatable-productos thead td.almacenes");
  for (let index = 0; index < cantidad_almacenes.length; index++) {
    let stock_number = $(cantidad_almacenes[index]).attr('data-id');
    let campo_id = 'prosto' + stock_number;
    columns.push({ data: campo_id, className: 'text-right', searchable: false });
  }

  columns.push({ data: 'prosto10', className: 'text-right', searchable: false });
  columns.push({ data: 'ProPeso', className: 'text-right', searchable: false, render: fixedNumber });
  columns.push({ data: 'BaseIGV', className: 'text-right', searchable: false });
  columns.push({ data: 'ISC', className: 'text-right', searchable: false });
  columns.push({ data: 'tiecodi', className: 'text-right', searchable: false });

  table_productos = $('#datatable-productos').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "ordering": false,
    "ajax": {
      "url": url_route_productos_consulta,
      "data": function (d) {
        return $.extend({}, d, {
          "campo_busqueda": $(".select-field-producto").val(),
          "grupo": $("[name=grupo_filter] option:selected").val(),
          "familia": $("[name=familia_filter] option:selected").val()
        });
      }
    },
    createdRow: function (row, data, index) {
      let info = {
        marca: data.marca,
        producto: data,
        unidades: data.unidades,
      };
      $(row).data('info', info);
    },
    "oLanguage": { "sSearch": "", "sLengthMenu": "_MENU_" },
    "initComplete": function initComplete(settings, json) {
      $('div.dataTables_filter input').attr('placeholder', 'Buscar producto');
    },
    "columnDefs": [
      { "width": "10px", "targets": 0 },
      { "width": "40px", "targets": 1 },
      { "width": "100px", "targets": 2 },
      { "width": "70px", "targets": 3 },
      { "width": "70px", "targets": 4 },
      { "width": "70px", "targets": 5 }
    ],
    "columns": columns
  });

  table_productos.columns.adjust().draw();
}


function open_modal() {
}

function showDespachoIsNecesary() {
  // let d = despachar || 0;
  let d = 0;

  if (typeof despachar != "undefined") {
    d = despachar;
  }

  if (d) {
    $('#modalDespacho').modal();;
  }
}



function init() {
  console.log("init")
  showHideCampoTraslados();
  $("[data-toggle=tooltip]").tooltip();
  headerAjax()
  select2_init();
  select2ubigeo_init();
  open_modal();
  initDatatables();
  events();
  initialFocus();
  date();
  showDespachoIsNecesary();
  saveCurrentMotivo()
  setProductoInputSearchDefaultFocus();
};

// Ejecutar todas las funciones
init();
