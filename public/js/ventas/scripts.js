window.canjeIds = [];
window.almacenVisualize = true;
window.columns_alms_hide = [];
window.isCanje = false;

function poner_data_cliente(data) {
  // 
  $("[name=cliente_documento]").select2('destroy')
  $("[name=cliente_documento]").empty()

  let text = data.PCRucc + " - " + data.PCNomb;
  let id = data.PCCodi

  $("[name=cliente_documento]").attr({
    'data-id': id,
    'data-text': text,
    'data-url': url_buscar_cliente_select2,
  });

  $("[name=tipo_documento_c]").val(data.tipo_documento);
  initSelect2("#cliente_documento");
}

function registrarCliente(nombre_documento) {
  let data = {
    value: nombre_documento
  };

  $("#load_screen").show();

  let funcs = {
    complete: function (info) {
      $("#load_screen").hide();
    },
    success: function (info) {
      let $cliente_documento = $('#cliente_documento');
      if ($cliente_documento.length) {
        if (!$cliente_documento.val()) {

          $cliente_documento.empty();

          let tipodocumento;
          switch (info.TDocCodi) {
            case "0":
            case 0:
              tipodocumento = "Ninguno"
              break;
            case "1":
            case 1:
              tipodocumento = "DNI"
              break;
            case "4":
            case 4:
              tipodocumento = "CARNET DE EXTRANGERIA"
              break;
            case "6":
            case 6:
              tipodocumento = "RUC"
              break;
            case "7":
            case 7:
              tipodocumento = "PASAPORTE"
              break;
            case "B":
              tipodocumento = "DOC.IDENT"
              break;
          }


          $("[name=tipo_documento_c]").val(tipodocumento);
          let text = info.PCRucc + " - " + info.PCNomb;
          $cliente_documento.select2('destroy');
          $cliente_documento.empty();
          $cliente_documento.attr('data-id', info.PCCodi);
          $cliente_documento.attr('data-text', text);
          initSelect2("#cliente_documento");
          // $("#modalCliente").modal('hide');
        }
      }
      //
    },
  };
  ajaxs(data, url_crear_cliente_simple, funcs);
}


// Devuelve un booleano si es un RUC válido
// (deben ser 11 dígitos sin otro caracter en el medio)
function isDni(valor) {
  // Codigo correcto
  function esnumero(campo) { return (!(isNaN(campo))); }

  if (esnumero(valor)) {
    if (valor.length == 8) {
      return true;
    }
  }
  return false
}



// Devuelve un booleano si es un RUC válido
// (deben ser 11 dígitos sin otro caracter en el medio)
function rucValido(valor) {
  // Codigo correcto
  function esnumero(campo) { return (!(isNaN(campo))); }
  if (esnumero(valor)) {
    if (valor.length == 8) {
      suma = 0
      for (i = 0; i < valor.length - 1; i++) {
        digito = valor.charAt(i) - '0';
        if (i == 0) suma += (digito * 2)
        else suma += (digito * (valor.length - i))
      }
      resto = suma % 11;
      if (resto == 1) resto = 11;
      if (resto + (valor.charAt(valor.length - 1) - '0') == 11) {
        return true
      }
    }
    else if (valor.length == 11) {
      suma = 0
      x = 6
      for (i = 0; i < valor.length - 1; i++) {
        if (i == 4) x = 8
        digito = valor.charAt(i) - '0';
        x--
        if (i == 0) suma += (digito * x)
        else suma += (digito * x)
      }
      resto = suma % 11;
      resto = 11 - resto

      if (resto >= 10) resto = resto - 10;
      if (resto == valor.charAt(valor.length - 1) - '0') {
        return true
      }
    }
  }
  return false
}

// window.IGV_VALUE = 1.18;
window.IGV_VALUE = igvBaseUno;
window.documento_guardado = false;
// window.IGV_PORCENTAJE =   18;
window.IGV_PORCENTAJE = igvBaseCero;
window.window.modal_guia_has_show = false;
window.producto_input_focus = null;
window.inicial_input_focus = null;
window.data_guardado = null;


$(document).ready(function (e) {
  var need_pago = true;
  function sumStock(value, data, info) {
    let sum = Number(info.prosto1) + Number(info.prosto2) + Number(info.prosto3) + Number(info.prosto4) + Number(info.prosto5) + Number(info.prosto6) + Number(info.prosto7) + Number(info.prosto8) + Number(info.prosto9);
    return fixedNumber(sum);
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

  let appCalculateItem =
  {
    data: {
      quantity: 0,
      price: 0,
      discounts: [21],
      taxs: [],
    }
  }

  function convertToBase1(val) {
    return "1." + val;
  }

  function callBackPago(data) {
    guiaSalida(data)
  }

  function setTipoGuia() {
    let isNC = $("[name=tipo_documento] option:selected").val() == "07";

    if (isNC) {
      let $selectTipo = $("[name=tipo_movimiento]");
      let data = $selectTipo.data('nc');
      agregarASelect(data, 'tipo_movimiento', 'Tmocodi', 'TmoNomb')
    }
  }


  function guiaSalida(data = false) {
    setTipoGuia();
    show_modal("hide", "#modalSunatConfirmacion");

    if (openVentana('almacen')) {
      if (data) {
        window.poner_data_inputs(data.guia_data, true, null, "data-de");
      }
      show_hide_modal("modalGuiaSalida", true, true);
    }
    else {
      go_listado()
    }
  }


  var enter_accion = false;
  var tr_pago = {};

  // var IGV_VALUE = 1.18;
  // var IGV_PORCENTAJE = 18;
  var IGV_VALUE = igvBaseUno;
  var IGV_PORCENTAJE = igvPorc;

  var executing_ajax = false;
  var modal_tipodocumento = "#modalSelectTipoDocumentoPago";
  var modales_select = $(".modal-seleccion");
  var table_clientes = $("#datatable-clientes");
  var tipo_guardado = null;
  var action_item = "create";
  var reg_number = /^\d*(\.\d{0,8})?\d$/;
  var table_items = $("#table-items");
  var current_product_data = null;

  var focus_orden = {
    'cliente_documento': 'forma_pago',
    'forma_pago': 'moneda',
    'moneda': 'tipo_cambio',
    'doc_ref': 'fecha_emision',
    'tipo_documento': 'serie_documento',
    'serie_documento': open_select2,
    'tipo_cambio': 'fecha_emision',
    'fecha_emision': check_tipo_documento,
    'fecha_vencimiento': check_tipo_documento,
    'producto_nombre': verificar_producto_unidad,
    'producto_unidad': 'producto_cantidad',
    'producto_cantidad': 'producto_precio',
    'producto_precio': verificar_descuento,
    'producto_dct': agregar_item,
    'producto_isc': agregar_item,
    'producto_igv': agregar_item,
    'producto_percepcion': agregar_item,
    'tipo_gratuito': agregar_item,
    "ref_documento": "ref_serie",
    "ref_serie": "ref_numero",
    "ref_numero": verifiy_factura_number,
    "ref_fecha": 'ref_motivo',
    "ref_motivo": 'producto_nombre',
    "ref_tipo": 'producto_nombre',
  };

  function verificar_descuento() {
    let $dcto = $("[name=producto_dct]");

    if ($dcto.val() != 0) {
      $dcto
        .focus()
        .select();
      return;
    }

    agregar_item();

  }

  function open_select2() {
    let $cliente_documento = $('#cliente_documento');

    if ($cliente_documento.val()) {
      producto_input_focus
        .select()
        .focus();
      return;
    }

    $cliente_documento.select2('open');
  }

  window.openVentana = function (ventana) {

    let opciones = "deuda caja almacen";

    if (opciones.indexOf(ventana) !== -1) {
    }
    if (ventana == "deuda") {
      return verificar_deudas == "1";
    }
    if (ventana == "caja") {
      return verificar_caja == "1" && need_pago;
    }
    if (ventana == "almacen") {
      return verificar_almacen == "1";
    }
  }

  function agregar_deudas_info(deudas) {
    if (deudas.data.length) {
      window.poner_data_inputs(deudas.cliente, true, null, 'data-n')
      window.poner_data_inputs(deudas, true, null, 'data-n')
      let tbody = $("#table_deuda");
      let deudas_data = deudas.data;
      tbody.empty();

      for (var i = 0; i < deudas_data.length; i++) {
        let deuda = deudas_data[i];
        $link = $("<a target='_blank'>" + deuda.link.text + "</a>").attr('href', deuda.link.src);
        let vtaoper = $("<td></td>").append($link);
        let tdVtaFvta = tdCreate(deuda.VtaFvta, false);
        let tdVtaFVen = tdCreate(deuda.VtaFVen, false);
        let tdmoncodi = tdCreate(deuda.moneda, false);
        let tdVtaImpo = tdCreate(deuda.VtaImpo, false);
        let tdVtaPago = tdCreate(deuda.VtaPago, false);
        let tdVtaSald = tdCreate(deuda.VtaSald, false);

        $("<tr></tr>")
          .append(vtaoper, tdVtaFvta, tdVtaFVen, tdmoncodi, tdVtaImpo, tdVtaPago, tdVtaSald)
          .appendTo(tbody);
      }

      show_modal("show", "#modalDeudas");
    }
    else {
      modal_guardar();
    }
  }


  function calculosItem(data) {
    let isGravada = data.DetBase.toLowerCase() == "gravada";
    let info = {
      // Total del precio y la cantidad
      total: 0,
      // Precio del producto
      precio: Number(data.DetPrec),
      // Cantidad
      cantidad: Number(data.DetCant),
      // Porcentaje de descuento
      dctoPorc: Number(data.DetDcto),
      // Porcentaje de ISC
      iscPorc: Number(data.DetISP),
      // Valor unitario inicial con IGV
      valorUnitarioXItem: Number(data.DetPrec),
      // Valor unitario inicial con ISC (valorUnitarioXItem / Porcentaje ISC)
      valorUnitarioXItemISC: 0, // Number(data.DetPrec),
      // Valor ISC Por Unidad (Cuando es el ISC por una unidad de producto)
      valorISCXUnidad: 0,
      // ISC Total
      valorISCTotal: 0,
      // Si el item tiene afectación de la bolsa
      icbper: Number(data.icbper),
      // Si el item tiene afectación de la bolsa
      icbperTotal: 0,
      // Valor de venta bruto  = valorUnitarioXItem * cantidad
      valorVentaBruto: 0,
      // Valor de venta por item    = valorVentaBruto - descuento   
      valorVentaPorItem: 0,
      // Descuento total
      descuentoTotal: 0,
      // Valor de venta bruto = (valorVentaBruto - descuentoTotal)
      valorVentaDescuento: 0,
      // IGV Total de la operación,
      igv: 0,
    }

    let incluye_igv = Number(data.incluye_igv)

    if (isGravada) {
      info.valorUnitarioXItem = incluye_igv ? (info.valorUnitarioXItem / IGV_VALUE) : info.valorUnitarioXItem;
    }

    // 2024 -- 2024 
    // Si tiene isc a el valor unitario habra que aplicarle el isc
    if (info.iscPorc) {
      // info.valorUnitarioXItem = info.valorUnitarioXItem / Number("1." + info.iscPorc);
      // info.valorUnitarioXItem = info.valorUnitarioXItem / Number("1." + info.iscPorc);
    }

    // Valor de venta Bruto
    info.valorVentaBruto = info.valorUnitarioXItem * info.cantidad;
    info.valorVentaPorItem = info.valorVentaBruto;

    // Calcular el descuento 
    if (info.dctoPorc) {
      info.descuentoTotal = (info.valorVentaBruto / 100) * info.dctoPorc;
      info.valorVentaPorItem -= info.descuentoTotal;
    }

    // Calcular el isc total
    info.valorISCTotal = info.iscPorc ? (info.valorVentaPorItem * Number('0.' + info.iscPorc)) : 0;

    // Calcular IGV    
    if (isGravada) {
      info.igv = ((info.valorVentaPorItem + info.valorISCTotal) / 100) * IGV_PORCENTAJE;
    }

    // Bolsa
    info.icbperTotal = info.icbper ? (icbper_unit) * info.cantidad : 0;

    // info.total = fixedNumber( info.valorVentaPorItem + info.igv + info.valorISCTotal );
    info.total = info.valorVentaPorItem + info.igv + info.valorISCTotal;

    let infoReturn = {
      cantidad: info.cantidad,
      descuentoTotal: info.descuentoTotal,
      baseImponible: info.valorVentaPorItem,
      baseImposibleIGV: info.valorVentaPorItem + info.igv,
      valorVentaPorItem: info.valorVentaPorItem,
      igv: info.igv,
      isc: info.valorISCTotal,
      icbper: info.icbperTotal,
      total: info.total,
    };

    // console.log("infoReturn", infoReturn );

    return infoReturn;
  }




  function calcular_item(data) {
    return calculosItem(data);
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
      icbper: 0,
      igv: 0,
      total_documento: 0,
      percepcion: 0,
      retencion: 0,
      total_importe: 0,
      total_cantidad: 0,
      total_peso: 0,
    };

    let data;

    $("#table-items tbody tr").each(function (index, dom) {
      data = JSON.parse($(dom).attr('data-info'));
      let oper_resultados = calcular_item(data);

      // console.log("oper_resultados", oper_resultados);

      let base = data.DetBase.toLowerCase();
      switch (base) {
        case 'gravada':
          info.gravadas += oper_resultados.baseImponible;
          break;
        case 'inafecta':
          info.inafectas += oper_resultados.baseImponible;
          break;
        case 'exonerada':
          info.exoneradas += oper_resultados.baseImponible;
          break;
        case 'gratuita':
          info.gratuitas += oper_resultados.total;
          break;
      }

      info.icbper += oper_resultados.icbper;
      info.total_cantidad += oper_resultados.cantidad;
      info.total_peso += Number(fixedValue(data.DetPeso));
      info.descuento += oper_resultados.descuentoTotal;
      info.igv += oper_resultados.igv;
      info.isc += oper_resultados.isc;
      info.total_importe += base == 'gratuita' ? 0 : Number(oper_resultados.total);
    });

    info.total_documento = info.total_importe;
    // info.percepcion = calc_percepcion(info.total_importe);
    info.percepcion = calc_percepcion(info.gravadas + info.inafectas + info.exoneradas);
    return info;
  }

  function cantidad_letras(num) {
    $(".cifra_cantidad").text(NumeroALetras(num));
    setMontosPagos();
  }

  function totalAnticipo() {
    let anticipo_value = $("[name=VtaTotalAnticipo]").val();
    if (!isNaN(anticipo_value) || anticipo_value != "") {
      return Number(anticipo_value) * window.IGV_VALUE;
    }
    return 0;
  }

  function calc_retencion(total) {
    let val = 0;

    if ($("[name=tipo_cargo_global]").val() == "retencion") {

      let retencion_porc = $("[name=cargo_global]").val();
      if (validateNumber(retencion_porc) && $.trim(retencion_porc) != "") {
        val = ((total / 100) * retencion_porc);
      }
    }

    return val;
  }


  function poner_totales_cant() {

    let info = sum_cant();
    let $cargo_global = $("[data-name=cargo_global]");


    // let anticipo_value
    info.total_importe -= totalAnticipo();
    // info.total_importe += calc_percepcion(info.total_importe);
    info.retencion = calc_retencion(info.total_importe);

    $cargo_global.val(0);

    for (prop in info) {

      if ((prop == "percepcion" || prop == "retencion") && Number(info[prop]) > 0) {
        // console.log("Global Cargo!", prop, info[prop] );
        $cargo_global.val(formatNumber(info[prop]));
      }
      else {
        $("[data-name=" + prop + "]").val(fixedValue(info[prop]));
      }
    }

    cantidad_letras(info.total_importe);
  }

  // notificaciones
  function notificaciones(mensaje, type = 'info', heading = '') {
    var info = {
      'heading': heading,
      'position': 'top-center',
      'hideAfter': 3000,
      'showHideTransition': 'slide'
    };

    $.toast({
      heading: info.heading,
      text: mensaje,
      position: info.position,
      showHideTransition: info.showHideTransition,
      hideAfter: info.hideAfter,
      icon: type,
      stack: false
    });
  };


  function fixedNumber(v, codigo = false, decimals = 2) {
    if (isNaN(v)) {
      return v;
    }
    return codigo ? v : (Math.round(v * 100) / 100).toFixed(decimals);
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

    var camposFiltados = "DetPrec,TieCodi,DetCodi,ProCodi,id_pago,TpgCodi,ItenNum,itemNum,TieCodi,UniCodi,Linea,Nombre,Marca,Moneda,NroDocumento,Fecha".split(",");

    if (camposFiltados.includes(campo)) {
      campo_sindecimales = true;
    }

    value = campo_sindecimales ? value : fixedNumber(value, campo_sindecimales);

    return td
      .text(value)
      .attr("data-campo", campo);
  }

  function serie_documento_ok(data) {
    // table_facturas.draw();
    select_tabla_facturas()
    $("[name=ref_motivo]").focus();
  };

  function serie_documento_error(data) {
    notificaciones("Serie documento no encontrado", "danger");
  };

  function verifiy_factura_number() {

    if (!verifyInputCliente()) {
      notiYFocus("cliente_documento", "Seleccione un cliente");
      return false;
    }

    if (!$("[name=ref_documento] option:selected").val().length) {
      notiYFocus("ref_documento", "Tiene que introducir primero el tipo de documento");
      return false;
    }

    if (!$("[name=ref_serie]").val().length) {
      notiYFocus("ref_serie", "Tiene que introducir primero la serie del documento");
      return false;
    }

    let buscar = $("[name=ref_numero]").val();

    // table_facturas.draw();

    if (!$(".modal.fade.in").length) {
      show_modal("show", "#modalSelectFactura");
    }

    return true;
  }


  function verifyInputCliente() {
    return $("[name=cliente_documento]").val();
  }

  function verifiy_tipo_documento() {
    if (verifyInputCliente()) {
      show_modal("show", modalSelectTipoDocumentoPago);
    }
    else {
      notiYFocus("cliente_documento", "Seleccione un cliente");
    }
  }

  function resolverName(nombre) {

    if ($("[name=producto_igv] option:selected").data('gratuita')) {
      let listTexts = [
        "*** PREMIO ***",
        "*** DONACIÓN ***",
        "*** RETIRO ***",
        "*** PUBLICIDAD ***",
        "*** BONIFICACIÓN ***",
        "*** ENTREGA TRABAJADORES ***"
      ];

      let text = "*** " + $("[name=tipo_gratuito] option:selected").text() + " ***";

      for (var i = 0; i < listTexts.length; i++) {

        if ((nombre.indexOf(listTexts[i]) != -1) && text != listTexts[i]) {
          nombre = nombre.replace(listTexts[i], "");
        }
      }

      nombre += " " + text;
    }

    return nombre;
  }

  function agregar_dias() {
    let dias = Number($("[name=forma_pago] option:selected").attr('data-dias'));
    let fecha_inicial = $("[name=fecha_emision]").attr("data-fecha_inicial");
    let fecha_actual = $("[name=fecha_emision]").val();

    if (dias == 0) {
      $("[name=fecha_referencia]").val(fecha_inicial);
      return;
    }

    else {
      let newDate = addDays(new Date(fecha_actual), dias + 1);
      $("[name=fecha_referencia]").datepicker("update", newDate);
    }
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

    else if (!$("[name=ref_documento] option:selected").val().length) {
      notiYFocus("tipo_cambio", "Es obligatorio poner el tipo de documento, cuando es nota de credito");
    }
  }

  function notiYFocus(inputFocus, notificacionMensaje, notificacionTipo = "error") {
    $("[name=" + inputFocus + "]").focus();
    notificaciones(notificacionMensaje, notificacionTipo);
  }
  // pago_dia
  // pago_dia
  function validarItem() {

    if (!$("[name=producto_codigo]").val()) {
      notiYFocus("producto_codigo", "Ponga el codigo del producto")
      return false;
    }

    if (!$("[name=producto_unidad] option").length) {
      notiYFocus("producto_codigo", "No ha seleccionado ningun producto todavia")
      return false;
    }

    else if (!$("[name=producto_nombre]").val().length) {
      notiYFocus("producto_nombre", "No puede dejar vacia la descripción del producto");
      return false;
    }

    else if (validateIsNotNumber("", "producto_cantidad")) {
      notiYFocus("producto_cantidad", "La cantidad del producto tiene que ser un numero");
      return false;
    }

    else if (validateIsNotNumber("", "producto_precio")) {
      notiYFocus("producto_precio", "El precio del producto tiene que ser un numero ---");
      return false;
    }

    const $inputPrecio = $("[name=producto_precio]");

    // if(isLimit){
    const precioValue = Number($inputPrecio.val());
    const minPrecio = Number($inputPrecio.attr('data-default'));
    if (precioValue < minPrecio) {
      notiYFocus("producto_precio", `El Precio ingresado no puede ser menor que el precio por defecto (${minPrecio})`);
      return false;
    }
    // }

    // 

    if (validateIsNotNumber("", "producto_dct")) {
      notiYFocus("producto_dct", "El descuento del producto tiene que ser un numero");
      return false;
    }

    if (validateIsNotNumber("", "producto_importe")) {
      notiYFocus("producto_importe", "El importe del producto no puede ser vacia");
      return false;
    }

    if ($("[name=producto_importe]").val() == "0") {
      notiYFocus("producto_importe", "El importe del producto no puede ser 0");
      return false;
    }

    if ($("[name=producto_precio]").val() == "0") {
      notiYFocus("producto_importe", "El precio no puede ser 0");
      return false;
    }

    if ($("[name=producto_cantidad]").val() == "0") {
      notiYFocus("producto_importe", "La cantidad no puede ser 0");
      return false;
    }

    let cant_caracteres = $("[name=commentario]").val().length;

    if (cant_caracteres > 500) {
      notiYFocus("commentario", `El comentario tiene ${cant_caracteres} caracteres el limite es de 500`);
      return false;
    }

    return true;
  }

  function agregar_item() {

    if (window.executing_ajax) {
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
    // modulo_restriccion_venta_por_stock
    // if(modulo_manejo_stock){
    //   if (cantidad > stock) {
    //     if (!confirm("Stock disponible es menor que la cantidad requerida, desea continuar?")) {
    //       $("[name=producto_cantidad]").focus().select();
    //       return;
    //     }
    //   }
    // }



    if (cantidad > stock) {
      if (modulo_restriccion_venta_por_stock) {
        notificaciones('La Cantidad suministrada supera al Stock Disponible', 'error');
        $("[name=producto_cantidad]").focus().select();
        return false;
      }

      else {
        if (modulo_manejo_stock) {
          if (!confirm("La Cantidad suministrada supera al Stock Disponible, desea continuar?")) {
            $("[name=producto_cantidad]").focus().select();
            return;
          }
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
        ProCodi1: current_product_data.producto.ProCodi1,
        DetPeso: current_product_data.producto.ProPeso,
        DetCodi: current_product_data.producto.ProCodi
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
        ProCodi1: trInfo.info.ProCodi1,
        DetCodi: trInfo.info.DetCodi
      };
    };


    let nombre = $("[name=producto_nombre]").val();
    // console.log( "nombre" , nombre )
    nombre = resolverName(nombre);

    info.DetCome = $("[name=commentario]").val();
    info.UniCodi = $("[name=producto_unidad] option:selected").val();
    info.DetUniNomb = $("[name=producto_unidad] option:selected").text();
    info.icbper = $("[name=icbper]").val();
    info.DetNomb = nombre;
    info.TipoIGV = $("[name=tipo_gratuito]").val();
    info.incluye_igv = Number($("[name=incluye_igv]").is(':checked'));
    info.DetUni = $("[name=producto_unidad]").val();
    info.DetCant = $("[name=producto_cantidad]").val();
    info.DetPrec = $("[name=producto_precio]").val();
    info.DetDcto = $("[name=producto_dct]").val();
    info.DetPercP = $("[name=producto_percepcion]").val();
    info.DetPercV = $("[name=producto_percepcion_importe]").val();
    info.DetBase = $("[name=producto_igv] option:selected").val();
    info.DetIGVP = $("[name=producto_igv] option:selected").attr('data-value');
    info.DetIGVV = $("[name=producto_igv_total]").val();
    info.DetISC = 0;
    info.DetISP = $("[name=producto_isc]").val();
    info.DetImpo = $("[name=producto_importe]").val();

    action_i() == "create" ? add_item(info) : edit_item(info);
    return;
  }

  function error_item() {
    console.log("ay un error en el item");
  }

  function modificar_tr(info, tr) {

    for (prop in info) {

      campo_sindecimales = false;
      let campo = prop;

      if (prop == "TieCodi" || prop == "DetUniNomb" || prop == "itemNum" || prop == "UniCodi" || prop == "DetCodi" || prop == "DetBase" || prop == "DetPrec") {
        campo_sindecimales = true;
      }
      // DetNomb
      if (campo_sindecimales) {
        tr.find("[data-campo=" + prop + "]").text(info[prop]);
      }
      else {
        tr.find("[data-campo=" + prop + "]").text(fixedValue(info[prop], campo));
      }
    }

    tr.find("[data-campo=DetNom]").text(info['DetNomb']);
  }


  function edit_item(info) {
    notificaciones("Item modificado exitosamente", "success");
    action_i('create');
    let tr = $("tr.seleccionando");

    hide_comment();

    tr.hide(500, function () {
      modificar_tr(info, tr);
      poner_totales_cant();
      tr
        .show(500)
        .removeClass("seleccionando");
    });

    tr.attr('data-info', JSON.stringify(info));
    cleanInputsGroup("producto", quitar_unidad);
    poner_totales_cant();

    if (!this.inputFocus) {
      producto_input_focus
        .select()
        .focus();
    }
  }

  function updateNumeracionItems() {
    $("#table-items tbody tr").each(function (index, dom) {
      let indexBase1 = index + 1;
      indexBase1 = indexBase1 < 10 ? "0".concat(indexBase1) : indexBase1;
      $(this).find('td:eq(0)').text(indexBase1);
    });
  }

  function add_item(info, fromForm = false, cotizacion = false, cleanItems = false, disabledBtnDelete = false) {
    show_comment_div();
    hide_comment();

    let unidades = fromForm ? current_product_data.unidades : info.Unidades;
    let tbody = table_items.find("tbody");

    if (cleanItems) {
      tbody.empty();
    }

    let html_actions =
      `<a href='#' class='btn modificar_item btn-xs btn-primary'> <span class='fa fa-pencil'></span> </a>
    <a href='#' class='btn eliminar_item ${disabledBtnDelete ? 'disabled' : ''} btn-xs btn-danger'> <span class='fa fa-trash'></span> </a>`;

    let trItem = $("<tr></tr>")
      .addClass('tr_item')
      .attr({
        'data-info': JSON.stringify(info),
        'data-unidades': JSON.stringify(unidades)
      });

    let tdCodValue = cotizacion ? info.DetCodi : info.DetCodi;

    let itemNume = table_items.find("tbody tr").length + 1;
    itemNume = itemNume < 10 ? ("0" + itemNume) : itemNume;
    let tdItem = tdCreate(itemNume, false, "itemNum");
    let tdBS = tdCreate(info.TieCodi, false, "TieCodi");
    let tdGra = tdCreate(info.DetBase, false, "DetBase");
    let tdCod = tdCreate(tdCodValue, false, "DetCodi");
    let tdUni = tdCreate(info.DetUniNomb, false, "DetUniNomb");
    let tdDes = tdCreate(info.DetNomb, false, "DetNom");
    // ----------------------------------------------------------
    // ---let tdMarca = tdCreate(info.Marca ,false , "Marc"); ---
    // -------------------------------------------------------
    let tdCant = tdCreate(info.DetCant, false, "DetCant");
    let tdDcto = tdCreate(info.DetDcto, false, "DetDcto");
    let tdIGP = tdCreate(info.DetIGVP, false, "DetIGVP");
    let tdPrecio = tdCreate(info.DetPrec, false, "DetPrec");
    let tdImporte = tdCreate(info.DetImpo, false, "DetImpo");
    let tdAccion = tdCreate("", false, "Acciones");
    tdAccion.html(html_actions);

    trItem.append(tdItem, tdBS, tdGra, tdCod, tdUni, tdDes, tdCant, tdPrecio, tdDcto, tdIGP, tdImporte, tdAccion);
    tbody.append(trItem);

    updateNumeracionItems();

    cleanInputsGroup("producto", quitar_unidad);


    if (this.noFocus) {
      console.log("quitar focus");
    }

    else {
      producto_input_focus
        .select()
        .focus();
    }

    habilitarDesactivarSelect("tipo_cambio", false);
    habilitarDesactivarSelect("moneda", false);

    poner_totales_cant();
    descuento_global();
  }

  function habilitarDesactivarSelect(select_name, activar = true) {
    let select = $("[name=" + select_name + "]", '.factura_div');

    if (select.length) {
      activar ? select.removeAttr('disabled') : select.attr('disabled', 'disabled');
    }
    else {
      activar ? $("." + select_name).removeClass('disabled') : $("." + select_name).addClass('disabled')
    }
  }

  function verificar_producto_unidad(fromTable = true) {
    ;
    if (fromTable) {
      $("[name=producto_unidad]").focus();
    }
  }

  function check_tipo_documento() {
    var tipo_documento = $.trim($("[name=tipo_documento] option:selected").text().toLowerCase());
    if (tipo_documento === "nota de credito" || tipo_documento === "nota de debito") {
      $("[name=ref_documento]").focus();
    }
    else {
      $("[name=producto_nombre]").focus();
    }
  }

  function defaultErrorAjaxFunc(data) {
    // console.log("error ajax", data );

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

  function ajaxs(data, url, funcs = {}) {
    funcs.mientras ? funcs.mientras() : null;
    $.ajax({
      type: 'post',
      url: url,
      data: data,
      success: function (data) {
        funcs.success ? funcs.success(data) : defaultSuccessAjaxFunc(data);
      },
      error: function (data) {
        $(".block_elemento").hide();
        funcs.error ? funcs.error(data) : defaultErrorAjaxFunc(data);
      },
      complete: function (data) {
        executing_ajax = false;
        funcs.complete ? funcs.complete(data) : null;
      }
    });
  };

  function poner_codigo_documento() {

    $("[name=serie_documento] , [name=ref_serie]").parent('.form-group').removeClass('has-error');
    let nro_codigo = $("[name=serie_documento] option:selected").attr('data-codigo');
    $(".nro_documento").text(nro_codigo);

    setTipoSerieDocRef();
  }


  function buscar_en_table(buscar, table = table_clientes) {
    table
      .search(buscar)
      .draw();
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


  function add_to_selected(select, data, name_value, text_value, adicional_ifo = []) {
    // <option name="name_value" > text_value </option>
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


  function is_nota(value) {
    return value == "07" || value == "08";
  }


  function setTipoSerieDocRef() {
    let serie = $("[name=serie_documento] option:selected").val();;

    let isFactura = serie.charAt(0) == "F";
    let tipoDocumentoRef = isFactura ? "01" : "03";

    $("[name=ref_documento]").find(`option[value=${tipoDocumentoRef}]`).prop('selected', true)
    $("[name=ref_serie]").val(serie);
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

    poner_cliente_defecto();

    if (is_nota(tipo_documento.val())) {
      setTipoSerieDocRef();
      div_datos.removeClass('block hide');
    }
    else {
      div_datos.addClass('block hide');
    }

    cambiar_condicion();

    const tipoGuiaSelected = $("[name=guia_tipo_asoc] option:selected");

    if (tipo_documento.val() == "52") {

      $("[name=guia_tipo_asoc] option[value=asociar]").prop('disabled', true)
      $("[name=guia_tipo_asoc] option[value=nueva_electronica]").prop('disabled', true)
    }
    else {
      $("[name=guia_tipo_asoc] option[value=asociar]").removeAttr('disabled')
      $("[name=guia_tipo_asoc] option[value=nueva_electronica]").removeAttr('disabled')
    }

    poner_codigo_documento();
  }



  function cambiar_condicion() {
    let $modal = $("#modalCondicionVenta");
    let td = $("[name=tipo_documento] option:selected").val();
    let is_proforma = td == "50";
    let $textarea = $modal.find("[name=condicion_venta]");
    let $title = $modal.find(".modal-title");

    console.log(td, is_proforma)
    let $check = $("#modalCondicionVenta [name=uso_individual]")


    // $check.prop('disabled' , is_proforma );

    let $label = $("#modalCondicionVenta .label-condicion-tipo-guardado")


    if (is_proforma) {
      $label.show();
      $textarea.val($textarea.attr('data-con_cot'));
      $title.text("Condición de Cotización");
    }

    else {
      $label.hide();
      // $check.prop('disabled' , is_proforma );
      $textarea.val($textarea.attr('data-con_ven'));
      $title.text("Condición de Venta");
    }
  }


  function poner_tipo_cliente(data) {
    let tipo = data.tipo_documento_c.TdocNomb;
    $("[name=tipo_documento_c]").val(tipo);
    // console.log( " poner_El tip ode cliente" , data );
  }

  function poner_unidades(data, item = null) {
    const unitSelected = item != null ? item.DetUni : null;
    const $productoUnidad = $("[name=producto_unidad]");

    $productoUnidad.empty();
    if (window.isCanje) {
      const option = `<option
      ${unitSelected == item.UniCodi ? 'selected' : ''}
      data-unidad="${item.DetUnid}" value="${item.UniCodi}">${item.DetUniNomb}</option>`;
      $productoUnidad.append(option)
    }

    else {
      let unidades = data.unidades;
      for (let i = 0; i < unidades.length; i++) {

        const unit = unidades[i];

        const option = $("<option></option>")
          .attr('value', unit.Unicodi)
          .attr('data-unidad', unit.UniA)
          .text(unit.UniAbre);
        // --------------------------------------------------------------------
        if (item != null) {
          if (unitSelected == unit.Unicodi) {
            option.attr('selected', 'selected');

            console.log("unit", unit);

            const unitPrecio = $("[name=moneda] option:selected").val() == '01' ? unit.UniPMVS : unit.UniPMVD;

            $("[name=producto_precio]")
              .attr('data-default', unitPrecio)
          }
        }
        $productoUnidad.append(option)
      }
    }

    cambiarUnidad();
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
    // --------------------
    else {
      // codigo_moneda = current_product_data.producto.moncodi;
      codigo_moneda =
        $("[name=moneda] option:selected").val();

      unidades = current_product_data.unidades || current_product_data.Unidades;
    }

    let precio;
    let moneda = $("[name=moneda] option[value=" + codigo_moneda + "]").text();

    let producto_unidad = $("[name=producto_unidad] option:selected");
    let unidad_select = null;
    let is_sol = Number($("[name=moneda] option:selected").attr('data-esSol'));

    for (let i = 0; i < unidades.length; i++) {
      if (unidades[i].Unicodi == producto_unidad.val()) {
        unidad_select = unidades[i];
        break;
      }
    }

    precio = is_sol ? unidad_select.UNIPUVS : unidad_select.UNIPUVD;
    const precio_min = is_sol ? unidad_select.UniPMVS : unidad_select.UniPMVD;
    const decimales = is_sol ? window.decimales_soles : window.decimales_dolares;

    $("[name=producto_precio]").val(fixedNumber(precio, false, decimales));
    $("[name=producto_precio]")
      .attr('data-default', fixedNumber(precio_min, false, decimales));

    if (precio == 0) {
      $("[name=producto_precio]").removeAttr('readonly');

    }
    else {
      if (canModifyPrecios == 0) {
        $("[name=producto_precio]").attr('readonly', 'readonly');
      }
    }
    calcular_importe();
  }

  function dcto_defecto(data) {
    $("[name=producto_dct]").val(descuento_defecto);
  }

  function setStockQuantity(data) {
    let stock = 'prosto' + window.numStock;
    let val = data[stock];
    $("[name=producto_stock]").val(val)
  }

  function poner_data_producto(data) {
    show_modal("hide", "#modalSelectProducto");
    data.producto.ProUnidades = null;
    $("[name=producto_cantidad]").val(1);

    let funcs_agregar = {
      "ProUnidades": poner_unidades,
      "ProDcto1": dcto_defecto,
      "prosto1": setStockQuantity,
    };

    current_product_data = data;
    // window.poner_data_inputs(data.producto, funcs_agregar);

    let incluyeIGV = data.producto.incluye_igv == "1";
    $("[name=incluye_igv]").prop('checked', incluyeIGV);
    $("[name=icbper]").val(data.producto.icbper);

    window.poner_data_inputs(data.producto, funcs_agregar);
    // nextFocus("producto_nombre");
    $("[name=producto_nombre]")
      .select()
      .focus();
    // producto_unidad
    set_precio();
  }

  function tipodocumento_selected(data) {
    $("[name=ref_documento] option:selected").val(data.TidCodi);
    $("[name=ref_serie]").focus();
    show_modal("hide", modal_tipodocumento);
  }

  function enter_table_ele_click() {
    let modal = $(".modal.fade.in");
    enter_table_ele(modal)
  }


  function update_inventario_producto() {
    $(".load_screen").show();

    let url = $(this).attr('data-route');
    let funcs = {
      success: function (data) {
        let $tr_selected = $("#datatable-productos tbody tr.select");
        // Poner valores del almacen
        for (const key in data) {
          let value = fixedValue(data[key]);
          let td_almacen_selector = `td.almacen-id-${key}`;
          // console.log({ td_almacen_selector, value })
          $tr_selected.find(td_almacen_selector).text(value);
        }

        // Poner total
        $tr_selected.find('td.total-almacen').text(fixedValue(data['total']));
      },

      complete: function () {
        // table_productos.draw();
        $(".load_screen").hide();
      }
    };

    ajaxs({}, url, funcs);
  }


  /**
   * 
   * @param {Json} data 
   */
  function error_buscar_producto(data) {
    show_modal("show", "#modalSelectProducto");
    if (this.campo) {
      let campo = this.campo == 'ProCodi' ? 'codigo' : 'codigo_barra';
      $(`.select-field-producto option[value=${campo}]`).prop('selected', true);
    }
    table_productos.search(this.id_busqueda).draw();
  }

  function buscar_producto(id, tr = null, callBack = null) {
    if (tr) {
      poner_data_producto(tr.data('info'))
    }
    else {
      buscarProducto(id, poner_data_producto)
    }
  }

  function buscarProducto(id, successCallBack, campo = 'ProCodi') {
    let data = {
      campo: campo,
      codigo: id,
    };


    let thisData = {
      id_busqueda: id,
      campo: campo
    }

    let funcs = {
      success: successCallBack,
      error: error_buscar_producto.bind(thisData)
    };
    ajaxs(data, url_buscar_producto_datos, funcs);
  }



  function enter_table_ele(modal) {
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
    // cursor_producto

    let nextInputNameOrFunc = focus_orden[elemento];
    if (typeof nextInputNameOrFunc == "string") {
      $("[name=" + nextInputNameOrFunc + "]")
        .focus()
        .select();
    }
    else {
      nextInputNameOrFunc();
      if (name == 'producto_cantidad' || name == 'producto_precio' || name == 'producto_dct') {
      }
    }
  }

  function setClienteDataAndNextInput(data) {
    let funcs = {
      'tipo_documento_c': poner_tipo_cliente,
    }

    window.poner_data_inputs(data, funcs);
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

      // console.log( "activar estos elementos", $(dom));
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
    // console.log("calculando porcentaje" , e );
    if (e) {
      if (e.keyCode === 13) return false;
    }

    // show_select_gratuito()
    // console.log("calculando porcentaje");

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

  function show_hide_gratuita(show = true) {
    if (show) {

      $(".col-comentario")
        .removeClass('col-md-12')
        .addClass('col-md-9')

      $(".col-gratuita").show();
    }
    else {
      $(".col-comentario")
        .removeClass('col-md-9')
        .addClass('col-md-12')

      $(".col-gratuita").hide();
    }

  }


  function show_select_gratuito() {
    let value = $("[name=producto_igv] option:checked").data('gratuita');

    if (value == null) {
      show_hide_gratuita(false);
    }
    else {
      if ($(".div_comentario").is(':hidden')) {
        show_comment_div();
      }

      show_hide_gratuita(true);
    }
  }

  function calcular_igv() {
    show_select_gratuito();
    calcular_importe();
  }

  // serch pro wraning
  function accionar_buscar_producto(e) {

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

    let value = $("[name=producto_cantidad]").val() * $("[name=producto_precio]").val();
    return fixedValue(value);

  }

  function calcular_importe() {

    let data = {
      DetPrec: Number($("[name=producto_precio]").val()),
      // Cantidad
      DetCant: Number($("[name=producto_cantidad]").val()),
      // Porcentaje de descuento
      DetDcto: Number($("[name=producto_dct]").val()),
      // Porcentaje de ISC
      DetISP: Number($("[name=producto_isc]").val()),
      // Incluye IGV
      incluye_igv: $("[name=incluye_igv]").is(':checked'),
      // Bolsa @TODO
      icbper: $("[name=icbper]").val(),
      // Base
      DetBase: $("[name=producto_igv] option:selected").val().toLowerCase()
    }

    let calculo_result = calculosItem(data);
    //  i want to live
    // console.log("calculo_result", calculo_result, fixedValue(calculo_result.total) );

    $("[name=producto_importe]").val(fixedValue(calculo_result.total));
  }

  function fixedValue(value) {
    if (value == undefined || typeof value == "object") {
      return 0.00;
    }

    return Math.round(value * 100) / 100;
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

    // console.log( "keyCode" , keyCode );

    // Subir o bajar
    if (keyCode === 40 || keyCode === 38) {
      if (modalIsOpen) {
        subir_bajar_table(keyCode, modalUp);
        return false;
      }
    }

    else if (keyCode == 115) {

      if (window.documento_guardado) {
        return false;
      }

      if ($("#modalGuardarFactura").is('.in')) {
        aceptar_guardado();
      }
      else {
        verificar_data_factura();
      }

      return false;
    }

    // Enter
    else if (keyCode === 13) {
      if (modalIsOpen) {
        enter_table_ele(modalUp);
        return false;
      }
    }

    return true;
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

  function select_item(e) {
    e.preventDefault();

    // current para modificar o desactivar
    let $this = $(this).parents('tr');

    // tr seleccionado

    let tr_selec = $this.parents("tbody").find(".seleccionando");
    let data = false;

    cleanInputsGroup("producto", quitar_unidad);

    // si existe el tr para seleccionar
    if (tr_selec.length) {

      if ($this.is(tr_selec)) {
        $this.removeClass('seleccionando');
        habilitarDesactivarSelect("eliminar_item", false);
        action_i("create");
      }

      else {
        tr_selec.removeClass('seleccionando');
        data = JSON.parse($this.attr('data-info'));
        $this.addClass("seleccionando");
      }
    }

    // si no existe
    else {
      $this.addClass('seleccionando');
      data = JSON.parse($this.attr('data-info'));
    }
    // 

    if (data) {
      action_i("edit");
      current_product_data = data;
      // console.log("data item", data);
      let unidades = { unidades: JSON.parse($this.attr('data-unidades')) };
      poner_unidades(unidades, data);
      let funcs_agregar = { "prosto1": setStockQuantity };
      window.poner_data_inputs(data, funcs_agregar, null, 'data-name_item');
      habilitarDesactivarSelect("eliminar_item", true);
      show_select_gratuito();
    }

  }

  function eliminar_item(e) {
    e.preventDefault();
    if (confirm("Esta seguro que desea eliminar este item?")) {
      let $tr = $(this).parents('tr');
      let isLastChild = $tr.is(':last-child');

      $tr.hide(1000, function () {
        if (action_i() != "create") {
          cleanInputsGroup("producto", quitar_unidad)
          action_i("create");
        }
        $tr.remove();

        console.log("length", $tr.parents('tbody').find('tr').length)

        if (!$tr.parents('tbody').find('tr').length) {
          $("[name=moneda]").prop('disabled', false)
        }

        if (!isLastChild) {
          updateNumeracionItems();
        }
        poner_totales_cant();
      });
    }
  }

  function show_comment_div(e = null) {
    if (!e == null) {
      e.preventDefault();
    }

    let div_com = $(".div_comentario");
    let div_reg = $(".div_regular");
    div_com.toggle();
    div_reg.toggle();
    if (div_com.is(':visible')) {
      div_com.find("input").focus();
    }
  }

  function hide_comment() {
    $(".div_comentario").hide();
  }


  function validar_documento() {
    $(".has-error").removeClass('has-error');

    var tipodocumentoCode = $(".box-body [name=tipo_documento] option:selected").val();

    // console.log( "tipodocumentoText", tipodocumentoText);
    if ($("[name=guia_remision]").is(':checked')) {
      let res = true;

      if ($("[name=direccion_partida]").val().length == 0) {
        notificaciones('La dirección de partida esta vacia', "warning");
        res = false;
      }

      else if ($("[name=direccion_llegada]").val().length == 0) {
        notificaciones('La dirección de llegada esta vacia', "warning");
        res = false;
      }
      else if ($("[name=ubigeo_llegada]").val().length == 0) {
        notificaciones('Es necesario el ubigeo', "warning");
        res = false;
      }

      if (!res) {
        $("#modalDespacho").modal();
        return false;
      }
    }

    // Validar guias
    let guias = [];
    let tipo_guia_asoc = $("[name=guia_tipo_asoc]").val();
    if (tipo_guia_asoc == "asociar") {

      let $select_guias = $(".select-guia-select2");

      console.log('$select_guias.length', $select_guias.length, $select_guias);

      for (let i = 0; i < $select_guias.length; i++) {
        let guia_id = $select_guias[i].value;

        if (guia_id) {
          if (guias.includes(guia_id)) {
            notificaciones('No se puede repetir la guia de remisión', 'error');
            $("#modalAsocGuia").modal();
            return false;
          }
          guias.push(guia_id)
        }

        else {
          notificaciones('Tiene que seleccionar un número de guia en el campo', 'error');
          $("#modalAsocGuia").modal();
          return false;
        }
      }
    }

    // if(  tipo_documento.text().toLowerCase().indexOf("boleta") == -1  ){
    if (tipodocumentoCode != false) {
      if (!verifyInputCliente()) {
        notiYFocus("cliente_documento", "Tiene que introducir el numero del documento del cliente");
        return;
      }
    }

    // Validar anticipo
    if ($("#anticipoChecked").is(':checked')) {
      if (!$("#documento_anticipo").val()) {
        notiYFocus("documento_anticipo", "Tiene que seleccionar un documento de anticipo");
        return;
      }
    }

    if (tipodocumentoCode == '07' || tipodocumentoCode == '08') {

      if (!$(".btn-tipo-nota.selected").length) {

        $(".btn-tipo-nota").addClass('border-red');

        setTimeout(() => {
          $(".btn-tipo-nota").removeClass('border-red');
        }, 1000);

        notiYFocus("ref_documento", "Tiene que elegir el nùmero del documento de referencia, seleccione una opciòn: Sistema o Manualmente para elegir/introducir el nùmero");
        return false;
      }

      // $("[name=ref_documento] option:selected")
      if (!$("[name=ref_documento] option:selected").val()) {
        notiYFocus("ref_documento", "Tiene que seleccionar un tipo de documento");
        return false;
      }

      if (!$("[name=ref_documento] option:selected").val().length) {
        notiYFocus("ref_documento", "Tiene que introducir el tipo de documento");
        return false;
      }

      if (!$("[name=ref_serie]").val().length) {
        notiYFocus("ref_serie", "Tiene que introducir la serie del documento");
        return false;
      }

      if (!$("[name=ref_numero]").val().length) {
        notiYFocus("ref_numero", "Tiene que introducir el numero del documento de referencia");
        return false;
      }

      if (!$("[name=ref_fecha]").val().length) {
        notiYFocus("ref_fecha", "Tiene que introducir la fecha del documento de referencia");
        return false;
      }

      if ($("[name=ref_tipo]").val() === null) {
        notiYFocus("ref_tipo", "Tiene que introducir el tipo de nota de credito");
        return false;
      }

      if ($.trim($("[name=ref_serie]").val().toLowerCase()) != $("[name=serie_documento]").val().toLowerCase()) {
        $("[name=serie_documento] , [name=ref_serie]").parents('.form-group').addClass('has-error');
        notiYFocus("ref_serie", "Tienen que coincidir el tipo de documento de referencia con el actual, por favor revisar.");
        return false;
      }

    }
    // RUC = TODO MENOS BOLETA
    // . = SOLO BOLETA
    // DNI NOTA CREDITO O NOTA DE DEBITO    

    // tipo documento cliente
    let tdc = $(".box-body [name=tipo_documento_c]").val();
    // tipo documento factura
    let tdf = $(".box-body [name=tipo_documento] option:selected").val();

    // El cliente con RUC, no puede hacer una compra con boleta
    if (tdf != "52" && tdf != "50") {
      if (tdc == "RUC" && tdf == "03") {
        notiYFocus("tipo_documento", "Los clientes con RUC, no puede hacer una compra de boleta");
        return false;
      }

      if (tdc == "Ninguno") {

        if (tdf == "07" || tdf == "08") {
          const serie = $("[name=serie_documento] option:selected").val().substr(0, 1);

          if (serie != "B") {
            notiYFocus("tipo_documento", "Con este cliente solo puede registrar Notas de Credito con serie 'B'");
            return false;
          }
        }

        else if (tdf != "03") {
          notiYFocus("tipo_documento", "Con este cliente solo puede registrar boletas");
          return false;
        }
      }

      // if (tdc == "Ninguno" && tdf != "03") {
      //   notiYFocus("tipo_documento", "Con este cliente solo puede registrar boletas");
      //   return false;
      // }

      if ((tdc == "DNI" || tdc == "CARNET DE EXTRANGERIA" || tdc == "PASAPORTE") && tdf == "01") {
        notiYFocus("tipo_documento", "No tiene RUC el cliente para facturar");
        return false;
      }

      if (!$("#table-items tbody tr").length) {
        notiYFocus("producto_nombre", "Tiene que introducir al menos un producto");
        return false;
      }

      if (tdf == '01' && tdf != '01') {
        notiYFocus("cliente_documento", "La factura solo tiene que ser con RUC");
        return false;
      }
    }

    // Valdiar la forma de pago

    let fp = $("[name=forma_pago] option:selected");


    if (fp.data('credit')) {

      let total_fp = 0
      $("[name=pago_monto]", '#modalFP').each(function (index, dom) {
        total_fp += Number(this.value)
      });

      let total_importe = total_documento = Number($("[name=total_importe]").val());

      if ($("[name=tipo_cargo_global]").val() == "retencion") {
        let total_retencion = Number($("[data-name=cargo_global]").val());
        total_documento -= total_retencion;
      }

      if ($("[name=detraccionItem] option:selected").val()) {
        let porcDetr = Number($("[name=detraccionItem] option:selected").attr('data-porc'));
        let totalDetraccion = ((total_importe / 100) * porcDetr);
        total_documento -= totalDetraccion;
      }

      total_fp = fixedNumber(total_fp);
      total_documento = fixedNumber(total_documento);
      if (total_fp != total_documento) {
        notiYFocus("cliente_documento", `El total ${total_fp} del fraccionamiento de pagos no coincide con el total del documento ${total_documento}`);
        $("#modalFP").modal('show');
        return false;
      }
    }

    return true;
  }

  function previsualizar_documento() {
    if (!validar_documento()) {
      return;
    }

    let data = get_data_documento();
    $("#load_screen").show();
    ajaxs(data, url_previsulizacion, {
      success: (response) => {
        showPDF(response.path_);
      },
      complete: response => $("#load_screen").hide()
    });
  }

  function showPDF(path, prev = true) {
    if (/Android|iPhone/i.test(navigator.userAgent)) {
      $("#modalData").find('.modal-dialog').attr('class', 'modal-dialog modal-xxl')
      $("#modalData").find('.modal-title').text('Prevializaciòn de documento');
      const $embedPDF = `<embed src="${path}" width="100%" height="800px">`;
      $("#modalData").find('.modal-body').empty();
      $("#modalData").find('.modal-body').append($embedPDF);
      $("#modalData").modal()
    }


    else {
      $("#modalData").find('.modal-dialog').attr('class', 'modal-dialog modal-xxl')

      let nombre = "";

      if (prev) {
        nombre = "Prevializaciòn de documento";
      }
      else {
        let path_arr = path.split('/');
        nombre = path_arr[path_arr.length - 1];
      }

      $("#modalData").find('.modal-title').text(nombre);
      const $embedPDF = `<embed src="${path}" width="100%" height="800px">`;
      $("#modalData").find('.modal-body').empty();
      $("#modalData").find('.modal-body').append($embedPDF);
      $("#modalData").modal()
    }
  }


  function verificar_data_factura() {
    if (!validar_documento()) {
      return;
    }

    let data = {
      'id_cliente': $("#cliente_documento").val()
    };

    if (openVentana('deuda')) {
      let funcs = {
        success: agregar_deudas_info
      };
      ajaxs(data, url_guia_salida, funcs);
    }

    else {
      modal_guardar();
    }

  }

  function eje_pago() {

    if (openVentana("caja") || need_pago) {

      setTimeout(function () {
        console.log("creando modal pagos");
        AppPago.is_static_modal = true;
        AppPago.set_id($("[name=codigo_venta]").val());
        AppPago.set_callback(go_listado);
        AppPago.create($("[name=medio_pago] option:selected").val());
        show_modal("hide", "#modalSunatConfirmacion");
      }, 1000);

    }

    else {

      go_listado(1000);

      setTimeout(function () {
        show_modal("hide", "#modalSunatConfirmacion");
        if (openVentana("almacen")) {
          guiaSalida();
        }
        else {
          go_listado(1000);
        }
      }, 1000);
    }
  }


  function respuesta_sunat(data) {
    // console.log("Succes respuesta de la sunat" , data )
    ms(data.data, 200);
    eje_pago();
  }


  function boton_sunat(e) {
    mandar_sunat($(this).is('.acept'));
  }

  function ms(msj, status) {
    $(".modal-title-s").text(msj);
    $(".load-button .carga").hide();

    let error = $(".load-button .error");
    let todo_ok = $(".load-button .check");

    if (status == 200) {
      error.remove();
      todo_ok.removeClass('hide');
    }
    else {
      error.removeClass('hide');
    }
  }

  function mandar_sunat(respuesta) {

    if (respuesta) {

      $(".modal-title-s").text("Enviando a la sunat");
      $(".acept , .canc").hide();
      $(".load-button").removeClass('hide');

      let data = {
        id_venta: $("[name=codigo_venta]").val(),
        ref_documento: $("[name=ref_documento] option:selected").val(),
        ref_serie: $("[name=ref_serie]").val(),
        ref_numero: $("[name=ref_numero]").val(),
        ref_motivo: $("[name=ref_motivo]").val(),
      };

      let funcs = {
        success: respuesta_sunat,
        complete: function (data) {
          eje_pago(data);
        },
        error: function (data) {
          ms(data.responseJSON.data, 500);
        }
      };

      ajaxs(data, url_route_send_sunat, funcs)
    }

    else {

      if (openVentana("caja")) {
        eje_pago();
      }

      else {
        go_listado(500);
        return;

        if (openVentana("almacen")) {
          guiaSalida();
        }
        else {
          go_listado(500);
        }
      }

    }

  }

  function preguntar_sunat() {
    show_modal("show", "#modalSunatConfirmacion", "static");
  }

  a = preguntar_sunat;

  function guardar_factura(data) {
    window.documento_guardado = true;
    window.data_guardado = data;

    if (data.imprecion_data.impresion_directa) {
      
      try {
        
        function impresionExitosa() {
          successStore(true)
        }

        function impresionError(data) {
          successStore(true);
        }

        let ticketPrint = new printTicket(
          data.imprecion_data.data_impresion,
          data.imprecion_data.nombre_impresora,
          data.imprecion_data.cantidad_copias,
          impresionExitosa,
          impresionError
          );

        console.log( ticketPrint )
          
        ticketPrint.errorFunc = function (data) {
          console.log( "errorFunc", data )
          successStore(true);
        }.bind(ticketPrint);
        
        
        ticketPrint.print();

      } catch (error) {
        console.log("error_print", error)
      }

    }

    successStore(data, true);

  }


  function successStore(show_window_print = true) {

    $("#modalData").on('hide.bs.modal', e => {

      if (window.data_guardado.need_factura) {
        setTimeout(preguntar_sunat, 500);
      }

      else {
        if (need_pago) {
          eje_pago();
        }
        else {
          go_listado();
        }
      }


    })


    // Div Guardar
    $(".div_esperando").hide();
    $(".div_guardar").show();

    need_pago = window.data_guardado.need_pago;

    $("[name=codigo_venta]").val(window.data_guardado.codigo_venta);
    $(".nro_documento").text(window.data_guardado.nro_documento);
    show_modal("hide", "#modalGuardarFactura");
    $("#guardarFactura").addClass('disabled');
    $("[data-de=nro_operacion]", "#modal").val(window.data_guardado.guia.nro_operacion);

    // Show window print
    if (show_window_print) {
      showPDF(window.data_guardado.url, false);
    }


    // Anterior

    // if (window.data_guardado.need_factura) {
    //   setTimeout(preguntar_sunat, 500);
    // }

    // else {
    //   if (need_pago) {
    //     eje_pago();
    //   }
    //   else {
    //     go_listado();
    //   }
    // }



  }


  function error_guardar_factura(data) {
    // console.log("error al guardar la factura", data);
    $(".div_esperando").hide();
    $(".div_guardar").show();
    defaultErrorAjaxFunc(data);
    window.executing_ajax = false
  }

  function modal_guardar() {
    show_modal("hide", "#modalDeudas");
    show_modal("show", "#modalGuardarFactura", "static")
  }


  function get_data_documento() {
    let items = [];
    let pagos = [];
    let guias = [];

    $("#table-items .tr_item").each(function (i, d) {
      let info = JSON.parse($(this).attr('data-info'));
      delete info.Unidades;
      items.push(info);
    });

    $("#modalFP .pago-fecha-item").each(function (i, d) {

      let $pago_dia = $(this);
      let info = {
        'PgoCodi': $pago_dia.find("[name=pago_codi]").val(),
        'dias': $pago_dia.find("[name=pago_dias]").val(),
        'monto': $pago_dia.find("[name=pago_monto]").val(),
        'fecha_pago': $pago_dia.find("[name=pago_fecha]").val()
      }

      pagos.push(info);
    });


    // Guias 
    let tipo_guia = $("[name=guia_tipo_asoc]").val();
    if (tipo_guia == "asociar") {
      let result = $(".select-guia-select2").each(function (i, d) {
        guias.push(d.value);
      });
    }

    let dcto_global_porc = $('[name=descuento_global]').is(':disabled') ? 0 : $('[name=descuento_global]').val();

    let data = {
      canje: Number(window.isCanje),
      canjeIds: window.canjeIds,
      condicion_individual: $("#modalCondicionVenta [name=uso_individual]").is(':checked'),
      condicion: $("#modalCondicionVenta [name=condicion_venta]").val(),
      codigo_venta: $("[name=codigo_venta]").val(),
      tipo_documento: $(".box-body  [name=tipo_documento]").val(),
      serie_documento: $("[name=serie_documento]").val(),
      nro_documento: $(".nro_documento").text(),
      cliente_documento: $("[name=cliente_documento]").val(),
      cliente_nombre: $("[name=cliente_nombre]").val(),
      moneda: $("[name=moneda]").val(),
      tipo_cambio: $("[name=tipo_cambio]").val(),
      forma_pago: $("[name=forma_pago] option:selected").val(),
      medio_pago: $("[name=medio_pago] option:selected").val(),
      fecha_emision: $("[name=fecha_emision]").val(),
      fecha_vencimiento: $("[name=fecha_referencia]").val(),
      Vtabase: $('[data-name=gravadas]').val(),
      VtaDcto: $('[data-name=descuento]').val(),
      descuento_global: dcto_global_porc,
      guia_remision: $('[name=guia_remision]').is(':checked'),

      /* Guia remisión */
      direccion_llegada: $('#modalDespacho [name=direccion_llegada]').val(),
      direccion_partida: $('#modalDespacho [name=direccion_partida]').val(),
      transportista: $('#modalDespacho [name=transportista]').val(),
      motivo_traslado: $('#modalDespacho [name=motivo_traslado]').val(),
      empresa: $('#modalDespacho [name=empresa]').val(),
      placa: $('#modalDespacho [name=placa]').val(),
      ubigeo: $('#modalDespacho .ubigeo option:selected').val(),

      // -------
      placa_vehiculo: $('[name=placa_vehiculo]').val(),

      guia_tipo: tipo_guia,
      guias: guias,
      id_almacen: $("[name=almacen_id]").val(),
      id_movimiento: $("[name=tipo_movimiento]").val(),

      VtaInaf: $('[data-name=inafectas]').val(),
      observacion: $('[name=observacion]').val(),
      VtaExon: $('[data-name=exoneradas]').val(),
      VtaGrat: $('[data-name=gratuitas]').val(),
      VtaISC: $('[data-name=isc]').val(),
      VtaIGVV: $('[data-name=igv]').val(),
      total_cantidad: $("[name=cantidad_total]").val(),
      total_peso: $("[name=peso_total]").val(),
      total_importe: $("[name=total_importe]").val(),
      vendedor: $("[name=vendedor]").val(),

      /* Detracción */
      hasDetraccion: $("[name=detraccion]").is(':checked'),
      detraccionPorc: $("[name=detraccionPorc] option:selected").val(),
      detraccionItem: $("[name=detraccionItem] option:selected").val(),

      /* Anticipos */
      hasAnticipo: $("[name=anticipoChecked]").is(':checked'),
      // anticipoTipoDocumento: $("[name=VtaTidCodiAnticipo] option:selected").val(),
      // anticipoCorrelativo: $("[name=VtaNumeAnticipo]").val(),
      anticipoDocumento: $("#documento_anticipo").val(),
      anticipoValue: $("[name=VtaTotalAnticipo]").val(),

      vendedor: $("[name=vendedor]").val(),
      nro_pedido: $("[name=nro_pedido]").val(),
      tipo_seleccion_ref: $(".btn-tipo-nota.selected").data('type'),


      doc_ref: $("[name=doc_ref]").val(),
      ref_documento: $("[name=ref_documento]").val(),
      ref_serie: $("[name=ref_serie]").val(),
      ref_numero: $("[name=ref_numero]").val(),
      ref_fecha: $("[name=ref_fecha]").val(),
      ref_motivo: $("[name=ref_motivo]").val(),
      ref_tipo: $("[name=ref_tipo]").val(),

      tipo_cargo_global: $("[name=tipo_cargo_global]").val(),
      cargo_global: $("[name=cargo_global]").val(),

      tipo_guardado: $("[name=tipo_guardado]:checked").val(),
      imprimir: $("[name=tipo_guardado]").is(':checked'),
      formato_impresion: $("[name=formato_impresion]").val(),

      // percepcion: $("[name=percepcion]").val(),

      items: items,
      pagos: pagos,
    }

    $("tr.seleccionando").each(function (index, dom) {
      let item_data = JSON.parse($(this).attr('data-info'));
      console.log("item data", item_data);
      data.items.push(item_data);
    });


    return data;
  }


  function aceptar_guardado(e) {

    e.preventDefault();


    if (window.executing_ajax) {
      return false;
    }

    window.executing_ajax = true;

    let funcs = {
      success: guardar_factura,
      error: error_guardar_factura,
      complete: function () {
        $("#aceptar_guardado").removeClass('disabled');
        window.executing_ajax = false;
      }
    }

    tipo_guardado = $("[name=tipo_guardado]:checked").val();
    let data = get_data_documento();
    $(".div_guardar").hide();
    $(".div_esperando").show();
    ajaxs(data, url_verificar_factura, funcs);

    return false;
  }

  function show_hide_adicional_info() {
    let $div_info = $(".info_adicional")
    var iconClass;
    let isVisible = $div_info.is(':visible');

    if (isVisible) {
      $div_info
        .hide()
        .addClass('hidden-xs')
    }
    else {
      $div_info
        .show()
        .removeClass('hidden-xs')
    }

    iconClass = isVisible ? 'fa fa-compress' : 'fa fa-expand';
    $(this).find('span')
      .removeAttr('class')
      .attr('class', iconClass)
  }


  function set_data_guia_salida(data) {
    window.poner_data_inputs(data, true, null, "data-de");
  }

  function poner_cuentas_banco() {
    let option = $("[data-db=BanCodi] option:selected");
    let data = option.data('cuentas');
    let data2 = option.attr('data-cuentas');
    console.log("cuentas de este banco", data, data2);
    agregarASelect(data, "CuenCodi", "CueCodi", "CueNume");
    cambiarNumOper();
  }

  function cambiarNumOper() {
    let banco = $("[data-db=BanCodi]").val();
    let cuenta = $("[data-db=CuenCodi]").val();
    let f = new Date();
    let year = f.getFullYear();
    let m = f.getMonth() + 1;
    let mes = (m < 10) ? "0" + m : m;
    let nuevo_nroperacion = banco.concat(banco, cuenta, year, mes);
    console.log("nuevo numero de operación", nuevo_nroperacion);
    $("[data-db=NumOper]").val(nuevo_nroperacion);
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


  function salir_guia() {
    show_modal("hide", "#modalGuiaSalida");
    go_listado()
  }

  function go_listado(tiempo = 1000) {
    setTimeout(function () {
      window.location.href = url_listado_pago;
    }, tiempo)
  }

  function guardar_guia_salida() {

    let funcs = {
      success: function (data) {
        go_listado();
        return;
      },
      error: function (data) {
        notificaciones(data,)
        return;
      }
    };
    let data = {
      'id_almacen': $("[name=almacen_id]").val(),
      'id_movimiento': $("[name=tipo_movimiento]").val(),
      'id_factura': $("[name=codigo_venta]").val(),
    };

    ajaxs(data, url_save_guiasalida, funcs)
    // console.log("aceptar guia de salida");

  }

  function headerAjax() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  }

  function initialFocus() {

    inicial_input_focus.focus();


    // console.og()
    // console.log( "inicialFocus", inicial_focus );
    // if (inicial_focus == "0") {
    //   $(".box-body [name=tipo_documento]").focus();
    // }    
    // else if (inicial_focus == "1") {
    //   producto_input_focus
    //     .select()
    //     .focus()
    // }
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

  function salir_alert() {

    if (confirm("Esta seguro de salir")) {
      go_listado();
    }

  }

  function accion_item(e) {
    e.preventDefault();
    let $t = $(this);
    agregar_item();
  }


  function calculate_dcto_global(dcto_gl = 0) {
    let resp = 0;
    // let dcto_gl = $("[name=descuento_global]").val();
    let totales = sum_cant();

    if (validateNumber(dcto_gl) && $("[data-name=total_importe]").val() != "0") {
      let total_venta = Number(totales.gravadas) + Number(totales.exoneradas) + Number(totales.inafectas) + Number(totales.percepcion);
      // console.log("total_venta", total_venta);
      return (total_venta / 100 * dcto_gl)
    }

    return resp;
  }

  function calculate_descuento(value, dcto) {
    // console.log("calculate descuento");
    let elementos = [
      $("[data-name=descuento]"),
      $("[data-name=gravadas]"),
      $("[data-name=inafectas]"),
      $("[data-name=exoneradas]"),
      $("[data-name=igv]"),
      $("[data-name=isc]"),
      $("[data-name=total_documento]"),
      $("[data-name=total_importe]"),
    ];

    for (var i = 0; i < elementos.length; i++) {

      let ele = elementos[i];
      let eleValue = Number(ele.val());
      let res;

      if (ele.is('[data-name=descuento]')) {
        res = eleValue + value;
      }
      else {
        res = eleValue ? eleValue - ((eleValue / 100) * dcto) : "0.00";
      }

      // console.log("res desc", res);

      ele.val(fixedValue(res));
      cantidad_letras($("[data-name=total_importe]").val());
    }

  }

  function descuento_global() {
    let $input_dcto_global = $("[name=descuento_global]");
    let dcto_global = Number($input_dcto_global.val());

    if (!isNaN(dcto_global) && dcto_global > 0) {
      // $("[name=percepcion]").attr('disabled', 'disabled').val(0);
      $("[name=tipo_cargo_global] option:eq(0)").prop('selected', true);
      $("[name=tipo_cargo_global]").prop('disabled', true);
      $("[name=cargo_global]").prop('disabled', true)
        .val('');
    }
    else {
      // $("[name=percepcion]").removeAttr('disabled');

      $("[name=tipo_cargo_global]").prop('disabled', false);

      if ($("[name=tipo_cargo_global]").val() == "percepcion") {
        $("[name=cargo_global]").prop('disabled', false)
          .val(0);
      }


    }

    poner_totales_cant();

    if (validateNumber(dcto_global) && dcto_global != "0") {
      let descuento = calculate_dcto_global(dcto_global);
      calculate_descuento(descuento, dcto_global);
    }
  }

  function ver_data_cliente(e) {
    e.preventDefault();
    let target = $(this).data('element');
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

      tr.append(tds);
      table.find("tbody").append(tr);
    }


  }

  function show_articulo_vendidos() {
    if (!$("[name=cliente_documento]").val()) {
      $("[name=cliente_documento]").focus();
      notificaciones("Tiene que seleccionar un cliente primero", "error");
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

    if (!serie_val.length) {

      notificaciones("Tiene que escribir la serie", "error");
      $("[name=serie_doc]").focus()
      return;
    }
    if (!num_val.length) {
      notificaciones("Tiene que escribir el numero del documento", "error");
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
    $("[name=moneda] option[value=" + data.moneda + "]").prop('selected', true);
    $(`[name=vendedor] option[value=${data.vendedor}]`).prop('selected', true);
    $("[name=nro_pedido]").val(data.nume);

    let items = data.items;
    let table = data.table;

    $("#table-items tbody").empty()

    for (let i = 0; i < items.length; i++) {
      add_item(items[i], false, true);
    }

    window.poner_data_inputs(data.cliente);
    poner_data_cliente(data.cliente);

    show_modal("hide", "#modalImportacion");

    notificaciones("Importación exitosa", "success");
    $("[name=serie_doc]").val("");
    $("[name=num_doc]").val("");

    poner_totales_cant();
    descuento_global();
  }

  function mostrar_cotizac() {

    show_modal("show", "#modalSelectCotizacion");

  }

  function mostrar_condi_venta() {
    show_modal("show", "#modalCondicionVenta", 'static');
  }

  function guardar_condicion() {

    let input_type_guardado = $("#modalCondicionVenta [name=uso_individual]");
    let condicion_textarea = $("#modalCondicionVenta [name=condicion_venta]");

    let individual = input_type_guardado.is(':checked');
    let is_cotizacion = $("[name=tipo_documento] option:selected").val() == "50";
    let type = is_cotizacion ? 'cotizacion' : 'venta';

    if (is_cotizacion) {
      if (individual) {

        condicion_textarea.attr('data-con_cot', condicion_textarea.val())
        $("#modalCondicionVenta").attr('data-comprobar_guardado', 'false');
        show_modal("hide", "#modalCondicionVenta");

        $("#modalCondicionVenta").attr('data-comprobar_guardado', 'true');
        return;
      }
    }

    $("#modalCondicionVenta").attr('data-comprobar_guardado', 'false');

    let data = {
      type: type,
      descripcion: $("[name=condicion_venta]").val()
    };

    let funcs = {
      success: function () {
        let attr = is_cotizacion ? 'data-con_cot' : 'data-con_ven';
        condicion_textarea.attr(attr, condicion_textarea.val());
        show_modal("hide", "#modalCondicionVenta");
      },
      complete: function () {
        $("#modalCondicionVenta").attr('data-comprobar_guardado', 'true');
      }
    }

    ajaxs(data, url_guardar_condicion, funcs);
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
    // console.log("Corre enviado exitosamente", data);
  }

  function confirm_enviar_mail() {
    if (confirm("Esta seguro de enviar por correo el documento")) {
      // console.log("enviar");

      activar_desactivar_email(false);

      let data = {
        'id_factura': $("[name=codigo_venta]").val(),
      };

      let funcs = {
        success: envio_exitoso,
        error: function (data) {
          let error_message = "Su correo no pude enviase";
          // console.log("error, ", data)

          activar_desactivar_email(true);

          if (data.responseJSON) {
            if (data.responseJSON.errors) {
              error_message = data.responseJSON.errors.Email[0]
            }
          }
          notificaciones(error_message, "error");

        },
      };
      ajaxs(data, url_enviar_email, funcs);
    }
  }

  function ver_pagos() {
    AppPagosIndex.init();
    AppPagosIndex.tipo_pago_default = $("[name=medio_pago]").attr('data-id');
    AppPago.set_callback(AppPagosIndex.show_notopenmodal.bind(AppPagosIndex));
    AppPagosIndex.set_id($("[name=codigo_venta]").val());
    AppPagosIndex.show_openmodal();
  }

  function anulacion_exitosa(data) {
    go_listado();

    // notificaciones( data.message , "success");    
    // $(".block_elemento").hide();
    // $(".anular_documento").hide();
    // $(".ticket_value")
    // .show()
    // .find(".ticket_text")
    // .text( data.ticket ); 
  }

  function anular_documento() {
    if (confirm("Desea Anular este documento")) {

      $(".block_elemento").show();

      let data = { id_factura: $("[name=codigo_venta]").val() }
      let funcs = { success: anulacion_exitosa }
      ajaxs(data, url_anular_documento, funcs);
    }
    // -------------------------------------------- \\
  }

  function verificacion_exitosa(data) {
    notificaciones(data.message, "success");
    $(".block_elemento").hide();
    $(".anular_documento").addClass('disabled');
  }

  function verificar_ticket(preguntar) {
    ejecutar = true;

    if (!(typeof preguntar == "boolean")) {
      ejecutar = confirm("Desea validar el ticket?")
    }

    if (ejecutar) {
      let data = { id_factura: $("[name=codigo_venta]").val() };
      let funcs = { success: verificacion_exitosa };
      ajaxs(data, url_verificar_ticket, funcs);
    }
  }

  function calcular_isc() {
    let $element = $("[name=producto_isc]");
    let porc_isc = Number($element.val());
    let value_isc = 0;

    if (!isNaN(porc_isc)) {
      value_isc = ((importe() / 100) * porc_isc);
    }

    return value_isc;

  }

  function quitar_estilos() {
    $("#datatable-factura_select").removeAttr('style');
  }

  function buscar() {
    let id = $("[name=producto_codigo]").val();

    if (!id.trim().length) {
      return;
    }
    buscar_producto(id);
  }

  function salir_pago_accion() {
    go_listado();
    return;

    if (create) {
      // show_modal("hide", AppPago.paren);
      // show_modal("hide", AppPago.paren);      
      $(AppPago.parent).modal('hide');
      guiaSalida();
    }
    else {
      show_modal("hide", "#modalGuiaSalida");
    }
  }

  /**
   * Establecer el campo de busqueda del producto donde va a estar el curso del cliente
   */
  // function establecerCursorInicial()
  // {
  // window.inicial_focus.focus();
  // }

  function crear_cliente() {
    table_clientes.search(ultimo_codigo_cliente);
    window.open($(this).data('url'));
  }


  function select2_init() {
    initSelect2(".ubigeo", "#modalDespacho");
    $('#cliente_documento').select2({
      placeholder: "Buscar Cliente",
      theme: 'default container-cliente-search',
      minimumInputLength: 1,
      allowClear: true,
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
        cache: false
      }
    });

    let url = $('#documento_anticipo').attr('data-url');
  }


  /**
   * Establecer cual sera elemento del que se va a poner el foco por defecto para la busqueda del producto
   */

  function setProductoInputSearchDefaultFocus() {

    if (cursor_producto == "0") {
      return producto_input_focus = $("[name=producto_codigo]");
    }

    if (cursor_producto == "1") {
      return producto_input_focus = $("[name=producto_nombre]");
    }

  }

  function setInicialFocus() {

    if (cursor_inicial == "0") {

      inicial_input_focus = $("[name=tipo_documento]");
    }

    else if (cursor_inicial == "1") {
      inicial_input_focus = producto_input_focus;
    }
  }



  function open_modal_cliente() {
    $("#modalCliente").modal();
  }

  function calc_percepcion(importeTotal = null) {

    let percPorc = 0;
    let value = 0;
    // Number($ele.val());
    let percepcion_input = $(".input_cargo_global").val();

    if ($(".input_tipo_cargo").val() == "percepcion") {

      if (validateNumber(percepcion_input)) {
        percPorc = percepcion_input;
      }

    }


    if (importeTotal == null) {
      importeTotal = sum_cant().total_importe;
    }

    value = ((importeTotal / 100) * percPorc)

    return value;
  }

  function inputPercepcion(percepcion_porc = 0) {
    // let percepcion = $("[name=percepcion]");
    let descuento = $("[name=descuento_global]")

    // let percepcion_porc = Number(percepcion.val());
    let percepcion_val = 0;

    if (!isNaN(percepcion_porc) && percepcion_porc > 0) {
      // descuento
      // .attr('disabled', 'disabled')
      // .val(0);
      // Prototype@TODO
    }

    else {
      // descuento
      //   .removeAttr('disabled')
      //   .val(0);
      // percepcion.val(percepcion_val);
    }


    poner_totales_cant();
    descuento_global();
  }

  function guiaRemisionShow() {
    let $docRef = $("[name=doc_ref]");
    let $guiaCheck = $("[name=guia_remision]");
    let $btnDivModalDespacho = $("#showModalDespacho");

    if ($guiaCheck.is(':checked')) {
      $("#modalDespacho").modal();
      $docRef.val($guiaCheck.data('guia'));
      $btnDivModalDespacho.removeClass('invisible');
    }
    else {
      $docRef.val('');
      $btnDivModalDespacho.addClass('invisible');
    }
  }


  function putInputAnticipoValue() {
    let $this = $("[name=VtaTotalAnticipo]");
    let val = $this.val();
    let $divTotalAnticipo = $("[name=anticipoValue]");

    if (isNaN(val) || $.trim(val) == "") {
      $divTotalAnticipo.val(0);
    }
    else {
      $divTotalAnticipo.val(val);
    }

    poner_totales_cant();
    descuento_global(true);
  }


  function guiaAction() {
    // 
    // viewGuia();
    // 
    // if( guiaNeedShow() ){
    //   // if( ! modal_guia_has_show ){
    //     $("#modalGuiaSalida").modal();
    //     modal_guia_has_show = true;
    //   // }
    // }

  }


  function addButtonSelect2Cliente(btnSearch = true) {

    let btnClasss = btnSearch ? 'search' : 'save';
    let btnText = btnSearch ? 'Buscar' : 'Guardar';

    let buttonAdd = $(`<span class='btn-add-cliente btn-add-cliente btn btn-sm btn-default'> <span class='fa fa-${btnClasss}'></span> ${btnText} </span>`)

    $(".select2-dropdown").append(buttonAdd);
  }

  function guiaNeedShow() {
    $("#modalGuiaSalida").modal();

    // viewGuia();
    // console.log("aaa");
    return $(".select_guia option:selected").attr('data-guia') == "1";
  }

  function viewGuia() {
    $("#modalGuiaSalida").modal();
    // if (guiaNeedShow()) {
    // $("#modalGuiaSalida").modal();
    // }
  }


  function establecerTipoDeBuscarDocumento(e) {
    e.preventDefault();

    let $dr_tipo = $("[name=ref_documento]");
    let $dr_serie = $("[name=ref_serie]");
    let $dr_numero = $("[name=ref_numero]");
    let $dr_fecha = $("[name=ref_fecha]");
    let $btnCurrent = $(this);
    let $btnSelected = $(".btn-tipo-nota.selected")
    let isSistema = $btnCurrent.is("[data-type=0]");

    // Si ya hay un boton seleccionado
    if ($btnSelected.length) {
      // Si es el mismo boton seleccionado el que esta presionando en este momento
      // Si es el boton de sistema abrir el modal, si es el de manual, no hacer nada obviamente
      if ($btnCurrent.is($btnSelected)) {
        if (isSistema) {
          // Abrir modal ....
          if (!verifiy_factura_number()) return;
        }
      }

      // Cuando la opciòn seleccionado es la opuesta
      else {

        // Si la opciòn seleccionado es del sistema 
        // Guardar los valores puestos de la opciòn manual y limpiar esos campos  
        // Abrir el modal
        if (isSistema) {

          // Abrir modal ....
          if (!verifiy_factura_number()) return;


          // Desabilitar
          $(".group_ref").prop('disabled', true);

          // Limpiar
          $(".group_ref").val("");

        }

        // Si la opciòn seleccionada es la manual,
        // Habilitar los campos
        // Poner los valores anteriores
        else {
          // Habilitar los los campos 
          $(".group_ref").prop('disabled', false);
        }

        // Cambiar la selecciòn al nuevo valor        
        $btnSelected.removeClass('selected');
        $btnCurrent.addClass('selected');
      } // end else 
    }


    else {

      if (isSistema) {
        // ........ Abrir modal ........
        if (!verifiy_factura_number()) {
          return
        }

        $(".group_ref").prop('disabled', true);
      }

      else {
        $(".group_ref").prop('disabled', false);
      }

      $btnCurrent.addClass('selected');
    }
  }


  function showModalFp() {
    $("#modalFP").modal({
      'action': 'show',
      'backdrop': 'static'
    });
  }

  function getFraccionPagos(total, partes) {
    let total_div = parseInt(total / partes);
    let sub_total = total_div * partes - 1;
    let ultima_parte = total - sub_total;

    let partes_arr = [];
    for (let i = 0; i < partes; i++) {

      let parte = i == partes - 1 ? ultima_parte : sub_total;
      partes_array.push(parte);
    }

    return partes_arr;
  }


  function getPartes(cant_partes, f_cantidad, u_cantidad) {
    let partes_arr = [];

    for (let i = 0; i < cant_partes; i++) {
      // console.log(i , cant_partes);

      let parte = i === cant_partes - 1 ? u_cantidad : f_cantidad
      partes_arr.push(parte);
    }
    return partes_arr;
  }


  function getFraccionPagos(total, partes) {
    let total_div = parseInt(total / partes);
    let total_mul = total_div * partes;

    if (total_mul == total) {
      return getPartes(partes, total_div, total_div);
    }

    let ultima_parte = (total_div + (total - total_mul)).toFixed(2);

    return getPartes(partes, total_div, ultima_parte);
  }

  function fechaEmisionActions() {

    if ($("[name=forma_pago] option:selected").data('dias') > 0) {
      setFechasPagos();
    }
  }

  function getFecha(fecha_actual, dias_agregar) {
    let date_js = addDays(new Date(fecha_actual), dias_agregar + 1);
    let year = date_js.getFullYear();
    let month = date_js.getMonth() + 1;
    month = month < 10 ? `0${month}` : $month;
    let day = date_js.getDate();
    day = day = day < 10 ? `0${day}` : $day;

    return `${year}-${month}-${day}`;
  }

  function setFechasPagos() {
    let fecha_actual = $("[name=fecha_emision]").val();

    $(".pago-fecha-item", "#modalFP").each(function (index, dom) {
      let $this = $(this);
      let cantidad_dias = Number($this.find('[name=pago_dias]').val())

      let fecha_dia = getFecha(fecha_actual, cantidad_dias);
      $this.find('.fecha-text').text(fecha_dia);
      $this.find('[name=pago_fecha]').val(fecha_dia);
    })
  }

  function setMontosPagos() {
    let $option = $("[name=forma_pago] option:selected");
    let dias = Number($option.data('dias'));
    let total_pagos = 0;

    if (dias) {
      let total_dias = $option.data('info').length;
      let total = Number($("[name=total_importe]").val());
      let partes = getFraccionPagos(total, total_dias);


      partes.forEach(element => {
        total_pagos += element
      });

      $(".pago-fecha-item", "#modalFP").each(function (index, dom) {
        let $this = $(this);
        $this.find('[name=pago_monto]').val(partes[index]);
      })
    }

    $(".pago_monto_calculado", "#modalFP").val(total_pagos);
  }


  function setPagosToModal() {
    let $option = $("[name=forma_pago] option:selected");
    let dias_info = $option.data('info');
    let $modal = $("#modalFP");
    let $content = $modal.find(".modal-body");

    // Eliminar
    $content.empty();

    let fecha_actual = $("[name=fecha_emision]").val();
    let total = $("[data-name=total_importe]").val();
    let total_dias = dias_info.length;
    let totalIsValid = total > 0

    let partes = totalIsValid ? getFraccionPagos(total, total_dias) : [];


    let $dia_element = $(
      `<div class='row row-header'>
        <div class='col-md-6'> <span class='text-header'> Fecha </span> </div>
        <div class='col-md-6'> <span class='text-header'> Monto </span>  </div>
      </div>`
    );

    $content.append($dia_element);

    for (let i = 0; i < total_dias; i++) {
      let dia = dias_info[i];
      let parte = totalIsValid ? partes[i] : 0;
      let dias = Number(dia.PgoDias);

      let date_js = addDays(new Date(fecha_actual), dias + 1);
      let year = date_js.getFullYear();
      let month = date_js.getMonth() + 1;
      month = month < 10 ? `0${month}` : month;
      let day = date_js.getDate();
      day = day < 10 ? "0" + day : day;
      let fecha_parte = `${year}-${month}-${day}`;

      let $dia_element = $(
        `<div class='row pago-fecha-item'>
        <div class='col-md-6'> <span class='fecha-text'> ${fecha_parte} </span>
          <input class='fechas-fp' name="pago_codi" type='hidden' value="${dia.PgoCodi}"/> 
          <input class='fechas-fp' name="pago_dias" type='hidden' value="${dia.PgoDias}"/> 
          <input name="pago_fecha" type='hidden' value="${fecha_parte}"/>
        </div>
        <div class='col-md-6'> <input name="pago_monto" class='form-control input-sm' value="${parte}"/> </div>
      </div>`);

      $content.append($dia_element);
    }

    // Total
    let $total = $(
      `<div class='row row-totales'>
        <div class='col-md-6'>
          <p class='text-total'> Total </p>
        </div>
        <div class='col-md-6'> 
          <input name="pago_monto_calculado" disabled class='form-control input-sm' value="${total}"/>
        </div>
      </div>`);

    let $aceptar = $(
      `<div class='row row-aceptar'>
        <div class='col-md-12'>
          <a href="#" class='btn btn-flat btn-primary btn-block aceptar_fraccionamiento'> Aceptar fraccionamiento </a>
        </div>
      </div>`);
    $content.append($total, $aceptar);
  }


  function showHiddenBtnModalFp() {
    let $option = $("[name=forma_pago] option:selected");
    let $btnModalFP = $(".addon-btn-modal-pago");
    let isCredit = $option.data('credit') == 1;


    if (isCredit) {
      // $btnModalFP.css('visibility', 'visible');
      $btnModalFP.show();
      setPagosToModal();
      showModalFp();
    }
    else {
      // $btnModalFP.css('visibility', 'hidden');
      $btnModalFP.hide();
    }

  }


  function getTotalPagos() {

    let total = 0;
    let valid_montos = true;

    $("[name=pago_monto]", "#modalFP").each(function (index, dom) {

      let value = $(this).val();

      if ($.trim(value) == "" || isNaN(value)) {
        valid_montos = false;
        value = 0;
      }

      else {
        value = Number(value);
      }

      total += value;
    });

    return {
      'total': total,
      'valid_montos': valid_montos,
    };
  }


  function calculateTotalesPagos() {
    $("[name=pago_monto_calculado]", "#modalFP").val(getTotalPagos().total);
  }


  function aceptarFraccionamiento(e) {
    e.preventDefault();

    let info_pagos = getTotalPagos();

    let total_pagos = fixedNumber(info_pagos.total);
    let total_importe = total_documento = Number($("[name=total_importe]").val());

    if (!info_pagos.valid_montos) {
      notificaciones("Todos los montos tiene que ser nùmeros", 'error');
      return;
    }

    // console.log(
    //   "tc",
    //   $("[name=tipo_cargo_global]").val(),
    //   Number($("[data-name=cargo_global]").val())
    // );

    $retencion_str = "";

    const hasRetencion = $("[name=tipo_cargo_global]").val() == "retencion";
    const hasDetraccion = $("[name=detraccionItem] option:selected").val();
    let totalRetencion = 0;
    let totalDetraccion = 0;

    if (hasRetencion) {
      totalRetencion = Number($("[data-name=cargo_global]").val());
      total_documento -= totalRetencion;
    }

    if (hasDetraccion) {
      const porcDetr = Number($("[name=detraccionItem] option:selected").attr('data-porc'));
      totalDetraccion = (total_importe / 100) * porcDetr;
      total_documento -= totalDetraccion;
    }

    // if ($("[name=tipo_cargo_global]").val() == "retencion") {
    // total_importe
    //   let total_retencion = Number($("[data-name=cargo_global]").val());
    //   total_documento -= total_retencion;
    //   $retencion_str = `(${total_retencion})`;
    // }

    total_documento = fixedNumber(total_documento);


    if (total_pagos != total_documento) {

      let message = `La suma de todos los pagos (${total_pagos}) debe ser igual a (${total_documento}) `;

      if (hasRetencion) {
        if (hasDetraccion) {
          message += ` = Importe Pagar (${total_importe}) - (Retenciòn (${totalRetencion})  + Detracciòn (${totalDetraccion}))`

        }
        else {
          message += `Importe Pagar (${total_importe}) - Retenciòn (${totalRetencion})`
        }
      }

      else if (hasDetraccion) {
        message += ` = Importe Pagar (${total_importe}) - Detracciòn (${totalDetraccion}))`;
      }

      // notificaciones(`La suma de todos los pagos (${total_pagos}) fraccionados debe ser igual al total del documento (${total_documento}) .  si hay retención, recuerde que el total real es el total del documento (${total_importe}) menos la retención ${$retencion_str}`, 'error');

      notificaciones(message, 'error');


      return false;

    }

    $("#modalFP").modal('hide');
  }


  function addProductoToTable(data) {
    let unidad = data.unidades[0];
    let is_sol = Number($("[name=moneda] option:selected").attr('data-esSol'));
    let precio = is_sol ? unidad.UNIPUVS : unidad.UNIPUVD;
    let igv = data.producto.BaseIGV.toLowerCase() == 'GRAVADA' ? IGV_PORCENTAJE : 0;
    let info = {
      DetBase: data.producto.BaseIGV,
      DetCant: 1,
      DetCodi: data.producto.ProCodi,
      ProCodi1: data.producto.ProCodi1,
      DetCome: "",
      DetDcto: descuento_defecto,
      DetIGVP: igv,
      DetIGVV: null,
      DetISC: 0,
      DetISP: data.producto.ISC,
      DetImpo: 0,
      DetNomb: data.producto.ProNomb,
      DetPercP: null,
      DetPercV: null,
      DetPeso: null,
      DetPrec: precio,
      DetUni: unidad.Unicodi,
      DetUniNomb: unidad.UniAbre,
      MarCodi: data.marca.MarCodi,
      Marca: data.marca.MarNomb,
      TieCodi: data.producto.tiecodi,
      TipoIGV: "11",
      UniCodi: unidad.Unicodi,
      Unidades: data.unidades,
      icbper: data.producto.icbper,
      incluye_igv: data.producto.incluye_igv,
    };


    let calculo_result = calculosItem(info);
    info.DetImpo = calculo_result.total;

    let add_item_ = add_item.bind({ noFocus: true });
    add_item_(info)
  }

  function editProductoToTable($tr) {
    let info = JSON.parse($tr.attr('data-info'));
    info.DetCant = Number(info.DetCant) + 1;
    let calculo_result = calculosItem(info);
    info.DetImpo = calculo_result.total;
    $tr.addClass('seleccionando');
    let edit_item_ = edit_item.bind({ inputFocus: true });
    edit_item_(info);
  }


  function searchProductoByCodigoBarra(codigo) {
    let $items = $(`#table-items tbody tr`);

    if ($items.length) {
      let result = false;
      $items.each(function (index, tr) {
        let $tr = $(tr);
        let data = JSON.parse($tr.attr('data-info'));
        if (data.ProCodi1 == codigo) {
          $items.removeClass('seleccionando');
          editProductoToTable($tr);
          result = true;
          return false;
        }
      })
      return result;
    }

    return false;
  }

  function removeFocusInElement() {
    if (document.activeElement != document.body) document.activeElement.blur();
  }

  function scanDetectionAction(codigo, cantidad) {
    codigo = codigo.toUpperCase();
    let result = searchProductoByCodigoBarra(codigo);


    // Si es falso es que no se consigio otro producto y por lo tanto, toca buscarlo
    if (!result) {
      buscarProducto(codigo, addProductoToTable, 'ProCodi1');
    }
  }


  //
  // seleccionar una opción por defecto al select
  function select_value(select_name, value) {
    let select = $("[name=" + select_name + "]");
    select.find('opcion').prop('selected', false);
    select.find("option[value=" + value + "]").prop('selected', true)
  }

  //

  function changeCargoGlobal() {
    let tipo_cargo_global = $("[name=tipo_cargo_global]").val();
    let $cargo_global = $("[name=cargo_global]");

    // console.log({ tipo_cargo_global })

    if (tipo_cargo_global) {

      $("[name=descuento_global]")
        .prop('disabled', true)
        .val('');

      $cargo_global
        .prop('disabled', false);

      if (tipo_cargo_global == "retencion") {
        $cargo_global
          .prop('disabled', true)
          .val('3')
      }

      else if (tipo_cargo_global == "percepcion") {
        $cargo_global.val('0')
      }

      poner_totales_cant();
    }

    else {

      $("[name=descuento_global]")
        .prop('disabled', false)
        .val(0);


      $cargo_global
        .prop('disabled', true)
        .val('');

      poner_totales_cant();
      descuento_global();
    }


  }

  function cargoGlobalInput() {
    let $tipo_cargo_global = $("[name=tipo_cargo_global]");
    let tipo_cargo_value = $tipo_cargo_global.val();

    let $cargo_global = $("[name=cargo_global]");
    let cargo_value = $cargo_global.val();


    if (isNaN(cargo_value) || $.trim(cargo_value) == "") {
      poner_totales_cant();
      descuento_global();
    }

    else {
      // console.log("Aqui estoy");
      if (tipo_cargo_value == "dcto_global") {
        // console.log("dcto_global ");
        descuento_global(cargo_value);
      }

      else if (tipo_cargo_value == "percepcion") {
        poner_totales_cant();
      }

      else if (tipo_cargo_value == "retencion") {

      }

    }
  }


  function guiaOption() {
    let guia_option = $(".guia_action").val();
    let $btn_guia_viewer = $(".modal-guia-viewer");
    let $btn_guia_asoc = $(".btn_guia_asoc");

    switch (guia_option) {
      case 'sin_guia':
        // $guia_tipo_asoc_div.hide();
        $btn_guia_viewer.hide();
        $btn_guia_asoc.hide();
        break;
      case 'nueva_interna':
      case 'nueva_electronica':
        // $guia_tipo_asoc_div.show();
        $btn_guia_viewer.show();
        $btn_guia_asoc.hide();
        break;
      case 'asociar':
        // $guia_tipo_asoc_div.hide();
        $btn_guia_viewer.hide();
        $btn_guia_asoc.show();
        break;
    }

  }


  function addNewSelectGuiaToAsociate() {
    let $boxContainer = $(".box-guias");
    let $select_guia = $(".plantilla-select-guia").clone(true);

    // plantilla-select-guia
    $select_guia.removeClass('plantilla-select-guia');
    $select_guia.addClass('select-guia');
    $select_guia.show();

    let date = new Date();
    let idSelect = date.getTime();
    // $select_guia.find('.select-guia-select2').attr('id',idSelect);
    $select_guia.find('select')
      .attr('id', idSelect)
      .addClass('select-guia-select2');

    $boxContainer.append($select_guia);

    // 
    let url = $(this).attr('data-url');

    $("#" + idSelect).select2({
      placeholder: "Buscar Guia de remisiòn",
      theme: 'default container-cliente-search',
      minimumInputLength: 1,
      allowClear: true,
      ajax: {
        url: url,
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
        cache: false
      }
    });


  }


  function deleteSelectGuiaToAsociate() {
    let $btnDelete = $(this);
    $btnDelete.parents('.select-guia').remove();
  }

  //
  function consultar_grupo_filter() {
    table_productos.search('');


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


  function cambiarUnidad() {
    const unidad = $("[name=producto_unidad] option:selected").attr('data-unidad')

    if (unidad == "KGM") {
      $(".calculate-peso").removeClass('hide');
      return;
    }

    $(".calculate-peso").addClass('hide');
  }

  function showCalculatorPeso() {
    const $modal = $("#modalCalculoPeso");

    const $peso = $modal.find(".peso")
    const $espesor = $modal.find("[name=espesor]")
    const $ancho = $modal.find("[name=ancho]")
    const $alto = $modal.find("[name=alto]")
    const $utilidad = $modal.find("[name=utilidad]")
    const $calculo = $modal.find("[name=calculo]")

    $espesor.val(0);
    $ancho.val(0);
    $alto.val(0);
    $utilidad.val(0);
    $calculo.val(0);
    $peso.text(current_product_data.producto.ProPeso)

    $("#modalCalculoPeso").modal(true);
  }


  function calculatePeso(e) {
    const $modal = $("#modalCalculoPeso");
    const $espesor = $modal.find("[name=espesor]")
    const $ancho = $modal.find("[name=ancho]")
    const $alto = $modal.find("[name=alto]")
    const $utilidad = $modal.find("[name=utilidad]")
    const gravedad = 7.85;
    const $calculo = $modal.find("[name=calculo]")

    //
    espesor_val = Number($espesor.val());
    ancho_val = Number($ancho.val()) / 1000;
    alto_val = Number($alto.val()) / 1000;
    utilidad_val = Number($utilidad.val());


    let calculado = (ancho_val * alto_val * espesor_val * gravedad);

    $calculo.val(calculado.toFixed(4));
  }

  function setPrecioCalculo() {

    const $modal = $("#modalCalculoPeso");

    const utilidad_val = Number($modal.find("[name=utilidad]").val());
    const calculo_val = Number($modal.find("[name=calculo]").val());

    if (isNaN(calculo_val) || isNaN(utilidad_val)) {
      notificaciones('Revise los datos puestos', 'error');
      return;
    }

    if (utilidad_val != 0) {
      const precio = Number($("[name=producto_precio]").val())

      const precio_new = precio + ((precio / 100) * utilidad_val);

      $("[name=producto_precio]").val(precio_new)
    }

    $("[name=producto_cantidad]").val(calculo_val);

    calcular_importe();

    let text = $("[name=producto_nombre]").val();

    text += ` (${$modal.find("[name=ancho]").val()}MM X ${$modal.find("[name=alto]").val()}MM)`;

    $("[name=producto_nombre]").val(text)
    $("#modalCalculoPeso").modal('hide');
  }

  function showHideAlmacenes() {
    const getIndexs = () => {

      if (window.columns_alms_hide.length) {
        return window.columns_alms_hide;
      }

      $("#datatable-productos thead .almacen-showhide").each(function () {
        window.columns_alms_hide.push($(this).attr('data-column'));
      })

    }

    columns = getIndexs();

    for (let index = 0; index < window.columns_alms_hide.length; index++) {
      var column = table_productos.column(window.columns_alms_hide[index]);
      column.visible(!column.visible());
    }

  }

  function events() {

    $("body").on('click', '.ver-mostrar-almacenes', showHideAlmacenes)

    $("#modalCalculoPeso").on('keyup', '.input-calculate', calculatePeso)

    // Initialize with options
    onScan.attachTo(document, {
      suffixKeyCodes: [13],
      reactToPaste: false,
      onScan: scanDetectionAction,
      onKeyDetect: function (iKeyCode) {
      }
    });

    $("#modalCalculoPeso").on('click', '#aceptarCalculo', setPrecioCalculo);

    $(".calculate-peso").on('click', showCalculatorPeso)

    $("#modalCondicionVenta .btn-example-toggle").on('click', function () {
      let isOpen = $(this).attr('data-open') == "true";
      if (isOpen) {
        $(".example-condicion").hide();
        $(this).attr('data-open', 'false');
      }
      else {
        $(".example-condicion").show();
        $(this).attr('data-open', 'true');
      }
    })

    $("#modalCondicionVenta").on('hide.bs.modal', function (e) {

      let comprobar_guardado = $("#modalCondicionVenta").attr('data-comprobar_guardado') == "true";

      if (!comprobar_guardado) {
        return true;
      }

      let $condicion_textarea = $("#modalCondicionVenta [name=condicion_venta]");
      let is_proforma = $("[name=tipo_documento] option:selected").val() == "50";
      let condicion_save = is_proforma ? $condicion_textarea.attr('data-con_cot') : $condicion_textarea.attr('data-con_ven');
      let condicion_current = $condicion_textarea.val();
      let has_changes = condicion_save != condicion_current;

      if (!has_changes) {
        return true;
      }

      if (confirm("Ha hecho cambios en la condición, esta seguro que desea salir sin guardar?")) {
        $condicion_textarea.val(condicion_save);
        $condicion_textarea.val(condicion_save);
        return true;
      }
      else {
        return false;
      }
    })


    $(".guia_action").on('change', guiaOption);

    $(".btn-add-new").on('click', addNewSelectGuiaToAsociate);

    $("#modalAsocGuia").on('click', '.btn-delete-new', deleteSelectGuiaToAsociate);



    $("#modalFP").on('keyup', '[name=pago_monto]', calculateTotalesPagos);

    $("#modalFP").on('click', '.aceptar_fraccionamiento', aceptarFraccionamiento);

    $("[name=forma_pago]").on('change', showHiddenBtnModalFp);

    $("[name=tipo_cargo_global]").on('change', changeCargoGlobal);

    $("[name=cargo_global]").on('keyup', cargoGlobalInput);

    $(".btn-forma-pago").on('click', showModalFp);

    $(".btn-tipo-nota").on('click', establecerTipoDeBuscarDocumento);

    $("body").on('click', '.btn-add-cliente', function (e) {

      let value = $.trim($('.select2-search__field').val());
      registrarCliente(value);
      e.preventDefault();
    })


    $("body").on('select2:opening', '#cliente_documento', function (e) {

      $(".btn-add-cliente").remove();
      if ($("#cliente_documento").val()) {
        return false;
      }
    });


    $("body").on('keyup', '.container-cliente-search .select2-search__field', function (e) {

      let value = $.trim(e.target.value);
      $(".btn-add-cliente").remove();

      if (value == "") {
        return false;
      }

      setTimeout(() => {

        let optionsLen = $(".select2-results__option--highlighted").length;

        if ($("#cliente_documento").val()) {
          $(".btn-add-cliente").remove();
          return;
        }


        if (isDni(value) || rucValido(value) && !optionsLen) {

          addButtonSelect2Cliente();
          return;
        }

        if (value.length > 2 && /^[a-zA-Z\s]*$/.test(value) && !optionsLen) {
          // console.log("Nombre", value);
          addButtonSelect2Cliente(false);
          // registrarCliente(value);
          return;
        }

        $(".btn-add-cliente").remove();


      }, 500);

    });

    $("body").on('change', '.select-field-producto', function (E) {
      table_productos.draw();
    });



    $("[name=grupo_filter]").on('change', consultar_grupo_filter);

    $("[name=familia_filter]").on('change', () => {

      table_productos
        .search('')
        .draw();
    });

    $(".modal-guia-viewer").on('click', viewGuia);

    $(".select_guia").on('change', guiaAction);

    $("[name=VtaTotalAnticipo]").on('keyup', putInputAnticipoValue);

    $("#detraccionChecked").on('change', function () {

      let $detraccionCheck = $(this);
      let $detraccionPorc = $("[name=detraccionPorc]");
      let $detraccionItem = $("[name=detraccionItem]");
      let isChecked = $detraccionCheck.is(':checked');

      $detraccionPorc.toggle(isChecked);

      if (isChecked) {
        $detraccionItem
          .removeAttr('disabled')
          .find("option:eq(1)").prop('selected', 'selected');
      }
      else {
        $detraccionItem
          .attr('disabled', 'disabled')
          .find("option:eq(0)")
          .prop('selected', 'selected');
      }

    })


    $("#documento_anticipo").on('select2:selecting', function (data) {

      let $input_total_anticipo = $("[name=VtaTotalAnticipo]");
      let data_doc_anticipo = data.params.args.data.data;
      $input_total_anticipo.val(data_doc_anticipo.total);
      putInputAnticipoValue();
    })



    $("#documento_anticipo").on('select2:unselecting', function (data) {
      $documento_anticipo = $("#documento_anticipo");
      $documento_anticipo.select2('destroy');
      $documento_anticipo.empty();
      initSelect2("#documento_anticipo");
      $("[name=VtaTotalAnticipo]").val("0");
      putInputAnticipoValue();
    })



    $("#anticipoChecked").on('click', function (e) {

      let cliente = $("#cliente_documento").val();
      let isChecked = this.checked;
      let isCheckedChange = !isChecked;
      let $documento_anticipo = $("#documento_anticipo");
      let $total_anticipo = $("[name=VtaTotalAnticipo]");

      if (isCheckedChange) {
        $documento_anticipo.select2('destroy');
        $documento_anticipo.empty();
        $documento_anticipo.prop('disabled', true);
        $total_anticipo.val("");
      }

      else {

        let tipo_documento_id = $("[name=tipo_documento]").val();

        if (tipo_documento_id != "01") {
          notificaciones('Solo se puede hacer factura como documento con anticipo', 'error');
          e.preventDefault();
          return false;
        }

        if (cliente) {
          $documento_anticipo.data('parameters', {
            'client_id': cliente,
            'tipo_documento_id': tipo_documento_id,
          });
          $documento_anticipo.removeAttr('disabled');
          initSelect2("#documento_anticipo");
        }

        else {
          notificaciones('Tiene que seleccionar un cliente', 'error');
          e.preventDefault();
          return false;
        }

      }


      /*



      let $anticipoCheck = $(this);
      let $anticipoTipoDocumento = $("[name=VtaTidCodiAnticipo]");
      let $anticipoCorrelativo = $("[name=VtaNumeAnticipo]");
      let $anticipoTotal = $("[name=VtaTotalAnticipo]");
      let isChecked = $anticipoCheck.is(':checked');

      if (isChecked) {

        $anticipoCorrelativo
          .removeAttr('readonly');

        $anticipoTotal
          .removeAttr('readonly');

        $anticipoTipoDocumento
          .removeAttr('disabled')
          .find('option:eq(1)')
          .prop('selected', true)
      }
      else {
        $anticipoTipoDocumento
          .attr('disabled', 'disabled')
          .find('option:eq(0)')
          .prop('selected', true)

        $anticipoCorrelativo
          .attr('readonly', 'readonly')
          .val('')

        $anticipoTotal
          .attr('readonly', 'readonly')
          .val('')

      }

      putInputAnticipoValue();

      */

    })


    $("select", '#modalSelectCotizacion').on('change', function () {
      table_cotizacion.draw();
    }),

      $("[name=guia_remision]").on('change', guiaRemisionShow);

    $("[name=incluye_igv]").on('change', calcular_importe);

    $('#cliente_documento').on('select2:selecting', function (data) {

      let tDocCodi = data.params.args.data.data.tipo_documento_c.TdocNomb;

      $(".row-cliente-adicional [name=direccion]").val(data.params.args.data.data.PCDire);

      $("[name=tipo_documento_c]").val(tDocCodi);
    });

    $('#cliente_documento').on('select2:unselecting', function (data) {

      if ($("#anticipoChecked").is(':checked')) {
        notificaciones('Para cambiar de cliente primero tiene que quitar el anticipo', 'error');
        return false;
      }

      $(".row-cliente-adicional [name=direccion]").val('');

    });

    $('#cliente_documento').on('select2:close', function (data) {
      producto_input_focus
        .select()
        .focus();
    });

    $("#newCliente").on('click', open_modal_cliente);

    $(".crear_cliente").on('click', crear_cliente);

    // $("[name=producto_codigo]").on('blur' , buscar )

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

    $(".acept , .canc").on('click', boton_sunat);

    $("[name=descuento_global]").on('keyup', descuento_global)

    $(".seguir_operacion").on('click', modal_guardar)

    // $("[name=producto_dct]").on('keyup', calcular_descuento)
    // $("[name=producto_isc]").on('keyup' , calcular_importe )

    $(".buscar_cliente").on('click', buscar_cliente);

    $("#salir_").on('click', salir_alert)

    $(".item-accion").on('click', accion_item);

    table_clientes.on('draw.dt', select_tabla_clientes);
    table_productos.on('draw.dt', select_tabla_productos);

    table_productos.on('draw.dt', () => {
      if (window.almacenVisualize) {
        window.almacenVisualize = false;
        showHideAlmacenes();
      }
    });

    table_cotizacion.on('draw.dt', select_tabla_cotizacion);

    // table_facturas.on('draw.dt', select_tabla_facturas);

    $("#datatable-productos,#datatable-clientes,#datatable-clientes, #datatable-factura_select,#datatable-cotizacion_select").on('click', "tbody tr", seleccion_elemento);

    $("#datatable-productos,#datatable-clientes,#datatable-factura_select").on('dblclick', "tbody tr", enter_table_ele_click);

    $("#datatable-productos").on('click', ".update-inventario", update_inventario_producto);

    $(".elegir_elemento").on('click', enter_table_ele_click)

    $("*").on("keydown", teclado_acciones);

    $("[name=producto_codigo],[name=producto_nombre]").on("keydown", accionar_buscar_producto);


    $("#boton_buscar").on("click", function (e) {
      e.preventDefault()
      show_modal("show", "#modalSelectProducto");
      let value_input = $("[name=producto_nombre]").val().trim();
      $(".select-field-producto").find("option[value=nombre]").prop('selected', true);
      table_productos.search(value_input).draw();
    });


    $("#boton_buscar_code").on("click", function (e) {

      e.preventDefault();
      let value_input = $("[name=producto_codigo]").val().trim();

      if (value_input) {
        buscar();
      }
      else {
        $(".select-field-producto").find("option[value=codigo]").prop('selected', true)
        table_productos.search(value_input).draw();
        show_modal("show", "#modalSelectProducto");
      }
    });




    // cambiar de foco carefull
    $("[name=fecha_emision],[name=tipo_documento],[name=serie_documento],[name=fecha_referencia],[name=moneda],[name=tipo_cambio],[name=forma_pago],[name=producto_unidad],[name=producto_cantidad],[name=producto_precio],[name=ref_documento],[name=producto_igv],[name=ref_serie],[name=ref_numero],[name=ref_fecha], [name=producto_isc],[name=producto_isc_other],[name=producto_percepcion],[name=producto_dct] ").on("keydown", cambiar_focos);

    $(".aceptar_guia").on('click', guardar_guia_salida);

    $("[data-db=CuenCodi]").on('change', cambiarNumOper);

    $(".totales #showFullInfo").on('click', show_hide_adicional_info);

    $("#guardarFactura").on('click', verificar_data_factura);
    $("#previsualizar").on('click', previsualizar_documento);


    $("#aceptar_guardado").on('click', aceptar_guardado);

    $(".agregar_comentario").on('click', show_comment_div);

    $("#table-items").on('click', '.modificar_item', select_item);

    $("#table-items").on('click', '.eliminar_item', eliminar_item);

    $(".salir_guia").on('click', salir_guia);

    $("[name=moneda]").on("change", moneda_precio_change);

    $("[name=producto_unidad]").on("change", set_precio);
    $("[name=producto_unidad]").on("change", cambiarUnidad);

    $("[name=producto_cantidad] , [name=producto_precio] , [name=producto_dct], [name=producto_isc] ").on("keyup", calcular_importe);

    //$("[name=producto_dct]").on("keyup",calcular_descuento);

    $("[name=producto_igv]").on("change", calcular_igv);

    $("[name=forma_pago]").on("change", agregar_dias);

    $("[name=fecha_emision]").on("change", fechaEmisionActions);


    $("[name=producto_percepcion]").on("keyup", calcular_porcentaje);

    $(".anular_documento").on("click", anular_documento);

    // tipo de documento
    $(".box-body [name=tipo_documento]").on("change", cambiar_tipo_documento);

    // serie documento
    $("[name=serie_documento]").on("change", poner_codigo_documento);

    // serie documento
    $("[name=percepcion]").on("keyup", inputPercepcion);

  }

  function formatNumber(value) {
    return fixedNumber(value);
  }


  function initDatatables() {

    function dataUnidadPrincipal(value, type, data, settings) {
      let dataUnidadFirst = data.unidades_[0];
      let valOutput = '';

      let columnsProperty = {
        5: { name: 'UniPUCD', decimales: window.decimales_dolares },
        6: { name: 'UniPUCS', decimales: window.decimales_soles },
        7: { name: 'UniMarg', decimales: 2 },
        8: { name: 'UNIPUVS', decimales: window.decimales_soles },
      }


      if (dataUnidadFirst) {
        valOutput = dataUnidadFirst[columnsProperty[settings.col].name];
      }

      return fixedNumber(valOutput, false, columnsProperty[settings.col].decimales);
    }

    function getRoute(id, url_movimiento = true) {
      return url_movimiento ?
        url_movimiento_producto.replace('xxx', id) :
        url_actualizar_almacen_producto.replace('xxx', id);

    }

    function addRouteToReporte(value, type, row, meta) {
      let route = getRoute(value);
      let route_update = getRoute(value, false);
      return `
      <a href="${route}" target="_blank" class="btn btn-xs btn-default btn-flat" title="Movimientos Compra/Venta"> <span class="fa fa-exchange"><span> </a>
      <a data-route="${route_update}"  href="#" class="btn btn-xs btn-default btn-flat update-inventario" title="Actualizar inventario"> <span class="fa fa-refresh"><span> </a>
      `
    }

    table_clientes = $('#datatable-clientes').DataTable({
      "processing": true,
      "serverSide": true,
      "lengthChange": false,
      "ordering": false,
      "ajax": url_route_clientes_consulta,
      "oLanguage": { "sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete": function initComplete(settings, json) {
        $('div.dataTables_filter input').attr('placeholder', 'Buscar');
      },
      "columns": [
        { data: 'PCCodi' },
        { data: 'TipCodi' },
        { data: 'PCRucc' },
        { data: 'PCNomb' },
      ]
    });


    let estado = "P";

    table_cotizacion = $('#datatable-cotizacion_select').DataTable({
      "processing": true,
      "serverSide": true,
      "lengthChange": false,
      "ordering": false,
      "ajax": {
        url: url_buscar_cotizaciones,
        "data": function (d) {
          return $.extend({}, d, {
            "estado": estado,
            "local": $("[name=local] option:selected", '#modalSelectCotizacion').val(),
            "tipo": $("[name=tidcodi_coti] option:selected", '#modalSelectCotizacion').val(),
          });
        }
      },
      "oLanguage": { "sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete": function initComplete(settings, json) {
        $('div.dataTables_filter input').attr('placeholder', 'Buscar');
      },
      "columns": [
        { data: 'CotNume', orderable: false, searchable: false },
        { data: 'CotFVta', orderable: false, searchable: false },
        { data: 'cliente_with.PCRucc', orderable: false, searchable: false },
        { data: 'cliente_with.PCNomb', orderable: false, searchable: false },
        { data: 'moneda.monabre', 'class': 'text-right', orderable: false, searchable: false },
        { data: 'cotimpo', render: function (data, x, y, z) { return fixedNumber(data) }, 'class': 'text-right', orderable: false, searchable: false },
        { data: 'usuario.usulogi', orderable: false, searchable: false },
      ]
    });

    $("#datatable-productos").one("preInit.dt", function () {
      let $button =
        $(`<select class='select-field-producto input-sm form-control'>
        <option value='codigo'>Codigo</option>
        <option value='nombre'>Nombre</option>
        <option value='codigo_barra'>Codigo Barra</option>
        </select>`);
      $("#datatable-productos_filter label").prepend($button);
      $button.button();
    });

    // Table Productos
    let product_columns = [
      { data: 'ProCodi', searchable: false },
      { data: 'unpcodi', searchable: false },
      { data: 'ProNomb', className: 'nombre_producto', searchable: false },
      { data: 'ProCodi', render: addRouteToReporte, searchable: false },
      { data: 'marca_.MarNomb', searchable: false },
    ];

    const ver_costos = Number($("#datatable-productos").attr("data-costos"));
    if (ver_costos) {
      product_columns.push({ data: 'ProMarg', className: 'text-right', searchable: false, render: dataUnidadPrincipal });
      product_columns.push({ data: 'ProPUCD', className: 'text-right', searchable: false, render: dataUnidadPrincipal });
      product_columns.push({ data: 'ProPUCS', className: 'text-right', searchable: false, render: dataUnidadPrincipal });
    }

    product_columns.push({ data: 'ProPUVS', className: 'text-right', searchable: false, render: dataUnidadPrincipal });
    product_columns.push({ data: 'ProPUVS', className: 'text-right total-almacen almacen-id-total', searchable: false, render: sumStock });

    // -----------------------------------------------------------------------------------
    let cantidad_almacenes = $("#datatable-productos thead td.almacenes");
    for (let index = 0; index < cantidad_almacenes.length; index++) {
      let stock_number = $(cantidad_almacenes[index]).attr('data-id');
      let campo_id = 'prosto' + stock_number;
      product_columns.push({ data: campo_id, className: 'text-right ' + 'almacen-id-' + stock_number, searchable: false });
    }
    // ---------------------------------------------------------------------------------
    product_columns.push({ data: 'prosto10', className: 'text-right', searchable: false });
    product_columns.push({ data: 'ProPeso', className: 'text-right', searchable: false, formatNumber });
    product_columns.push({ data: 'BaseIGV', className: 'text-right', searchable: false });
    product_columns.push({ data: 'ISC', className: 'text-right', searchable: false });
    product_columns.push({ data: 'tiecodi', searchable: false });


    table_productos = $('#datatable-productos').DataTable({
      "processing": true,
      "serverSide": true,
      "language": { search: "", searchPlaceholder: "Search..." },
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
        $('div.dataTables_filter input').attr('placeholder', ' Buscar Aqui');
      },
      "columnDefs": [
        { "width": "10px", "targets": 0 },
        { "width": "40px", "targets": 1 },
        { "width": "100px", "targets": 2 },
        { "width": "70px", "targets": 3 },
        { "width": "70px", "targets": 4 },
        { "width": "70px", "targets": 5 }
      ],
      "columns": product_columns
    });

    // , visible: false

    table_productos.columns.adjust().draw();


    // table_facturas = $('#datatable-factura_select').DataTable({
    //   "processing": true,
    //   "serverSide": true,
    //   "lengthChange": false,
    //   "ordering": false,
    //   "ajax": {
    //     "url": url_verificar_serie,
    //     "data": function (d) {
    //       return $.extend({}, d, {
    //         "codigo": $("[name=ref_serie]").val(),
    //         "tipo_documento": $("[name=ref_documento]").val(),
    //         "ruc_cliente": $("[name=cliente_documento]").val(),
    //       });
    //     }
    //   },

    //   "oLanguage": { "sSearch": "", "sLengthMenu": "_MENU_" },
    //   "initComplete": function initComplete(settings, json) {
    //     $('div.dataTables_filter input').attr('placeholder', 'Buscar factura');
    //   },
    //   "columns": [
    //     { data: 'VtaSeri' },
    //     { data: 'VtaNumee' },
    //     { data: 'VtaFvta' },
    //     { data: 'VtaImpo' },
    //   ]
    // });


  }

  function clearInputsInit() {
    if (create) {
      cleanInputsGroup('cliente');
      cleanInputsGroup('producto');
    }
    else {
      let v = $(".nro_documento").val();
      $(".nro_documento").attr('placeholder', v);
      $(".nro_documento").val(v);
    }
  }

  function clearDataTable() {
    $("#datatable-factura_select").removeAttr('style');
    $("#datatable-productos thead td").removeAttr("style");
    $("#datatable-productos thead td").removeAttr("style");
  }

  function poner_cliente_defecto() {
    let $tipo_documento = $("[name=tipo_documento]");
    let $cliente_documento = $("#cliente_documento");
    // 
    if ($tipo_documento.val() == "03" || $tipo_documento.val() == "52") {
      if (!$cliente_documento.val()) {
        let cliente_default = $cliente_documento.data('cliente_default');
        poner_data_cliente(cliente_default);
      }
    }

    else {
      if ($cliente_documento.val() === "00001") {
        $("#cliente_documento").val(null).trigger('change');
      }
    }
  }

  function addDocFromCanje(documentos) {
    // |-------|--------|-------|-------|-------|-------|
    window.isCanje = true;
    let itemsToAdd = {}
    const tc = Number($("[name=tipo_cambio]").val());
    const moneda = $("[name=moneda]").val();
    $("#table-items tbody tr").empty();
    window.canjeIds = [];
    // -------- canjeIds --------
    for (const id in documentos) {
      const documento = documentos[id];
      window.canjeIds.push(id);
      const items = documento.items;
      for (let index = 0; index < items.length; index++) {
        const item = items[index];
        const detCodi = item.DetCodi;

        if (itemsToAdd[detCodi]) {
          let importe = Number(item.DetImpo);
          if (moneda != documento.MonCodi) {
            importe = documento.MonCodi == "01" ? importe / tc : importe * tc;
          }

          itemsToAdd[detCodi].DetCant += Number(item.DetCant) * Number(item.Detfact);
          itemsToAdd[detCodi].DetImpo += importe;
        }
        else {
          itemsToAdd[detCodi] = {
            TieCodi: item.Estado,
            DetCodi: detCodi,
            DetBase: item.DetBase,
            DetUniNomb: item.DetUnid,
            DetNomb: item.DetNomb.replace(/&quot;/g, '"'),
            incluye_igv: item.incluye_igv,
            UniCodi: item.UniCodi,
            DetIGVV: item.DetIGVV,
            DetISP: 0,
            Marca: item.MarNomb,
            DetCant: Number(item.DetCant) * Number(item.Detfact),
            DetDcto: 0,
            DetIGVP: item.DetIGVP,
            DetImpo: Number(item.DetImpo),
            DetCome: '',
            Unidades: {}
          }
        }
      }
    }

    window.canjeIds = [...new Set(window.canjeIds)];

    let first = true;

    for (const id in itemsToAdd) {

      let clean = false;

      if (first) {
        clean = true;
        first = false;
      }

      // DavidGoggins-DavidGoggins
      const info = itemsToAdd[id];

      if (info.incluye_igv) {
        info.DetPrec = fixedNumber(info.DetImpo / info.DetCant);
      }
      else {
        info.DetPrec = fixedNumber((info.DetImpo / item.DetIGVV) / info.DetCant);
      }

      info.DetPrec = fixedNumber(info.DetImpo / info.DetCant);
      add_item(info, false, false, clean, true);
    }
  }

  function firefox() {
    if (create) {
      $("[name=ref_numero],[name=ref_serie],[name=ref_documento] ").val('');
    }
  }
  function init() {
    $("[data-toggle=tooltip]").tooltip();
    headerAjax(),
      select2_init();
    initDatatables();
    events();
    setProductoInputSearchDefaultFocus();
    setInicialFocus();
    clearInputsInit();
    firefox();
    poner_codigo_documento();
    poner_cliente_defecto();
    date();
    clearDataTable();
    initialFocus();

    canjeAppNew.setProcessFunc(addDocFromCanje);
  };
  init();
});