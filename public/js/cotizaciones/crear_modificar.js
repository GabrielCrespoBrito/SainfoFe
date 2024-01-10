$(document).ready(function (e) {

  window.almacenVisualize = true;
  window.columns_alms_hide = [];
  window.isSoles = null;
  var enter_accion = false;
  var tr_pago = {};
  // var IGV_VALUE = 1.18;
  // var IGV_PORCENTAJE = 18;
  var IGV_VALUE = igvBaseUno;
  var IGV_PORCENTAJE = igvPorc;
  var icbper_unit = 0.20;
  var modal_cliente = "#modalSelectCliente";
  var executing_ajax = false;
  var modal_producto = "#modalSelectCliente";
  var modal_factura = "#modalSelectFactura";
  var modal_tipodocumento = "#modalSelectTipoDocumentoPago";
  var modales_select = $(".modal-seleccion");
  var table_clientes = $("#datatable-clientes");
  var tipo_guardado = null;
  var items_agregate = [];
  var action_item = "create";
  var reg_number = /^-?\d*\.?\d*$/;
  var table_items = $("#table-items");
  var current_product_data = null;
  var show_deuda = false;
  var totales = {};
  var focus_orden = {
    'cliente_documento': 'forma_pago',
    'forma_pago': 'moneda',
    'moneda': 'tipo_cambio',
    'doc_ref': 'fecha_emision',
    'tipo_cambio': 'fecha_emision',
    'fecha_emision': check_tipo_documento,
    'fecha_vencimiento': check_tipo_documento,
    'producto_nombre': verificar_producto_unidad,
    'producto_unidad': 'producto_cantidad',
    'producto_cantidad': 'producto_precio',
    'producto_precio': 'producto_dct',
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


    if (data.DetDcto == "0") {
      resultado.descuento = dcto;
    }
    else if (data.DetIGVP == "0") {
      resultado.descuento = Number(data.DetImpo / 100) * dcto
    }
    else {
      // console.log("(IGVP > 0)" , resultado.valor_igv_total);      
      resultado.descuento = (resultado.valor_igv_total / 100) * dcto;
    };

    resultado.valor_venta_total = resultado.valor_igv_total - resultado.descuento;

    // IGV 
    resultado.igv = resultado.valor_venta_total * igvBaseCero;
    // resultado.igv = resultado.valor_venta_total * 0.18;


    return resultado;
  }


  function sumStock(value, data, info) {
    let sum = Number(info.prosto1) + Number(info.prosto2) + Number(info.prosto3) + Number(info.prosto4) + Number(info.prosto5) + Number(info.prosto6) + Number(info.prosto7) + Number(info.prosto8) + Number(info.prosto9);
    return fixedNumber(sum);
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
      icbper: 0,
      subtotal: 0,
      total_documento: 0,
      percepcion: 0,
      total_importe: 0,
      total_cantidad: 0,
      total_peso: 0,
    };

    let data = null;

    $("#table-items tbody tr").each(function (index, dom) {

      data = JSON.parse($(dom).attr('data-info'));
      let precio = Number(fixedValue(data.DetPvta));

      // console.log("Data del item" , data);
      let oper_resultados = calculosItem(data);


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

      info.total_cantidad += Number(fixedValue(data.DetCant));
      info.total_peso += Number(fixedValue(data.DetPeso));
      info.descuento += oper_resultados.descuentoTotal;
      info.igv += oper_resultados.igv;
      info.isc += oper_resultados.isc;
      info.icbper += oper_resultados.icbper;
      info.subtotal += oper_resultados.baseImponible;
      info.total_importe += data.DetBase == 'GRATUITA' ? 0 : Number(fixedValue(oper_resultados.total));
    });

    info.total_documento = info.total_importe;

    return info;
  }

  function cantidad_letras(num) {
    $(".cifra_cantidad").text(NumeroALetras(num));
  }

  function poner_totales_cant() {
    let info = sum_cant();

    console.log("poner_totales_cant", info);

    for (prop in info) {
      let ele = $("[data-name=" + prop + "]");
      // console.log("error poniendo en" , prop , info[prop] );
      ele.val(fixedValue(info[prop]));
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


  function fixedNumber(v, codigo = false, decimal = 2) {
    return isNaN(v) ? v : codigo ? v : Number(v).toFixed(decimal);
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
    // console.log( "campo", campo );

    var camposFiltados = "DetPrec,TieCodi,id_pago,ProCodi,DetCodi,TpgCodi,ItenNum,itemNum,TieCodi,UniCodi,Linea,Nombre,Marca,Moneda,NroDocumento,Fecha".split(",");

    if (camposFiltados.includes(campo)) {
      campo_sindecimales = true;
    }

    return td
      .text(fixedNumber(value, campo_sindecimales))
      .attr("data-campo", campo);
  }

  function serie_documento_ok(data) {
    table_facturas.draw();
    select_tabla_facturas()
    $("[name=ref_motivo]").focus();
  };

  function serie_documento_error(data) {
    notificaciones("Serie documento no encontrado", "danger");
    console.log("serie error", data);
  };

  function resolverName(nombre) {

    if ($("[name=producto_igv] option:selected").data('gratuita')) {
      let listTexts = [
        "*** PREMIO ***",
        "*** DONACION ***",
        "*** RETIRO ***",
        "*** PUBLICIDAD ***",
        "*** BONIFICACION ***",
        "*** ENTREGA TRABAJADORES ***"
      ];

      let text = "*** " + $("[name=tipo_gratuito] option:selected").val() + " ***";

      for (var i = 0; i < listTexts.length; i++) {

        if ((nombre.indexOf(listTexts[i]) != -1) && text != listTexts[i]) {
          nombre = nombre.replace(listTexts[i], "");
        }
      }

      nombre += " " + text;
    }

    return nombre;
  }


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
    table_facturas.draw();

    if (!$(".modal.fade.in").length) {
      show_modal("#modalSelectFactura", "show");
    }

    return;
  }



  function verifyInputCliente() {
    let ele = $("[name=cliente_documento]");
    return ele.val() !== null
  }

  function verifiy_tipo_documento() {
    if (verifyInputCliente()) {
      show_modal(modalSelectTipoDocumentoPago, "show");
    }
    else {
      notiYFocus("cliente_documento", "Seleccione un cliente");
    }
  }

  function agregar_dias() {
    let dias = Number($("[name=forma_pago] option:selected").attr('data-dias'));
    // let fecha = $("[name=fecha_referencia]").val();
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
    if (isNaN(num)) {
      return 0.00;
    }
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
    if (!$("[name=producto_codigo]").val()) {
      notiYFocus("producto_codigo", "Ponga el codigo del producto")
      return false;
    }

    else if (!$("[name=producto_nombre]").val().length) {
      notiYFocus("producto_nombre", "No puede dejar vacia la descripcion del producto");
      return false;
    }

    else if (validateIsNotNumber(null, "producto_cantidad")) {
      notiYFocus("producto_cantidad", "La cantidad del producto tiene que ser un numero");
      return false;
    }

    else if (validateIsNotNumber(null, "producto_precio")) {
      notiYFocus("producto_precio", "El precio del producto tiene que ser un numero");
      return false;
    }


    const $inputPrecio = $("[name=producto_precio]");

    const precioValue = Number($inputPrecio.val());
    const minPrecio = Number($inputPrecio.attr('data-default'));
    if (precioValue < minPrecio) {
      notiYFocus("producto_precio", `El Precio ingresado no puede ser menor que el precio por defecto (${minPrecio})`);
      return false;
    }



    if (validateIsNotNumber(null, "producto_dct")) {
      notiYFocus("producto_dct", "El descuento del producto tiene que ser un numero");
      return false;
    }

    if (validateIsNotNumber(null, "producto_importe")) {
      notiYFocus("producto_importe", "El importe de la compra no puede estar vacia");
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


    return true;
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

    // console.log( "current_product_data", current_product_data );
    // return;
    // stock
    let stock = Number($("[name=producto_stock]").val());
    let cantidad = Number($("[name=producto_cantidad]").val());
    let precio = Number($("[name=producto_precio]").val());
    let is_sol = Number($("[name=moneda] option:selected").attr('data-esSol'));

    if (modulo_manejo_stock) {
      if (cantidad > stock) {
        if (!confirm("Stock disponible es menor que la cantidad requerida, desea continuar?")) {
          $("[name=producto_cantidad]").focus().select();
          return;
        }
      }
    }

    let info;

    // console.log( "accion ejecutando " , action_i() );

    if (action_i() == "create") {

      info = {
        DetCodi: current_product_data.producto.ProCodi,
        Unidades: current_product_data.unidades,
        Marca: current_product_data.marca.MarNomb,
        MarCodi: current_product_data.marca.MarCodi,
        TieCodi: current_product_data.producto.tiecodi,
        DetPeso: current_product_data.producto.ProPeso,
        DetBase: current_product_data.producto.BaseIGV
      };
    }

    else {
      let trInfo = $("tr.seleccionando").data();
      // console.log(trInfo);
      // return;

      info = {
        DetCodi: trInfo.info.DetCodi,
        Unidades: trInfo.unidades,
        Marca: trInfo.info.Marca,
        MarCodi: trInfo.info.MarCodi,
        TieCodi: trInfo.info.TieCodi,
        DetPeso: trInfo.info.ProPeso,
        DetBase: trInfo.info.DetBase,
      };
    };


    let nombre = $("[name=producto_nombre]").val();
    nombre = resolverName(nombre);
    info.UniCodi = $("[name=producto_unidad] option:selected").val();
    info.DetUni = $("[name=producto_codigo]").val();
    info.DetUniNomb = $("[name=producto_unidad] option:selected").text();
    info.DetNomb = nombre;
    info.DetCant = $("[name=producto_cantidad]").val();
    info.DetPrec = $("[name=producto_precio]").val();
    info.DetDcto = $("[name=producto_dct]").val();
    info.DetDeta = $("[name=commentario]").val();
    info.DetPercP = $("[name=producto_percepcion]").val();
    info.DetPercV = $("[name=producto_percepcion_importe]").val();
    info.DetIGVP = $("[data-namedb=proigvv]").val();
    info.DetIGVV = $("[name=producto_igv_total]").val();
    info.DetISC = $("[name=producto_isc]").val();
    info.DetISP = $("[name=producto_isc_other]").val();
    info.DetImpo = $("[name=producto_importe]").val();
    info.incluye_igv = Number($("[name=incluye_igv]").prop('checked'));
    info.icbper = $("[name=icbper]").val();
    console.log("info", info);
    let success_func = action_i() == "create" ? add_item(info) : edit_item(info);
    return;

    show_comment_div();
    let funcs = {
      success: success_func,
    }

    // console.log("info a validar", info);

    ajaxs(info, url_verificar_item_info, funcs);
  }

  function error_item() {
    console.log("ay un error en el item");
  }

  function modificar_tr(info, tr) {
    let noDecimals = "TieCodi,DetNomb,DetBase,itemNum,UniCodi,DetCodi,Marca,MarcaNomb,DetPrec".split(",");
    for (prop in info) {
      let value = noDecimals.includes(prop) ? info[prop] : info[prop];
      tr.find("[data-campo=" + prop + "]").text(value);
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

    tr.attr('data-info', JSON.stringify(info));
    cleanInputsGroup("producto", quitar_unidad);
    action_item = "create";
    poner_totales_cant();

    producto_input_focus
      .select()
      .focus();
  }


  function add_item(info, fromForm = false) {
    show_comment_div();

    console.log("info a poner en el tr", info);

    let unidades = fromForm ? current_product_data.unidades : info.Unidades;

    let tbody = table_items.find("tbody");
    let trItem = $("<tr></td>")
      .addClass('tr_item')
      .attr({
        'data-info': JSON.stringify(info),
        'data-unidades': JSON.stringify(unidades)
      });

    let itemNume = table_items.find("tbody tr").length + 1;
    itemNume = itemNume < 10 ? ("0" + itemNume) : itemNume;
    let tdBS = tdCreate(info.TieCodi, false, "TieCodi");
    let tdGra = tdCreate(info.DetBase, false, "DetBase");
    let tdItem = tdCreate(itemNume, false, "itemNum");
    let tdCod = tdCreate(info.DetCodi, false, "DetCodi");
    let tdUni = tdCreate(info.DetUniNomb, false, "DetUniNomb");
    let tdDes = tdCreate(info.DetNomb, false, "DetNom");
    let tdMarca = tdCreate(info.Marca, false, "Marc");
    let tdCant = tdCreate(info.DetCant, false, "DetCant");
    let tdDcto = tdCreate(info.DetDcto, false, "DetDcto");
    let tdISC = tdCreate(info.DetISC, false, "DetISC");
    let tdIGP = tdCreate(info.DetIGVP, false, "DetIGVV");
    let tdPrecio = tdCreate(info.DetPrec, false, "DetPrec");
    let tdImporte = tdCreate(info.DetImpo, false, "DetImpo");
    let tdAccion = tdCreate("", false, "Acciones");
    let html_actions = "<a href='#' class='btn modificar_item btn-xs btn-primary'> <span class='fa fa-pencil'></span> </a><a href='#' class='btn eliminar_item btn-xs btn-danger'> <span class='fa fa-trash'></span> </a>";

    tdAccion.html(html_actions);
    trItem.append(tdItem, tdBS, tdGra, tdCod, tdUni, tdDes, tdMarca, tdCant, tdPrecio, tdDcto, tdImporte, tdAccion);
    tbody.append(trItem);

    let total = Number($("[name=producto_importe]").val());
    let total_importe = total + Number(info.DetPrec);
    // console.log("total y total_importe", total , total_importe);
    $("[name=total_importe]").val(total);

    cleanInputsGroup("producto", quitar_unidad);
    $("[name=producto_nombre]").focus();


    producto_input_focus
      .select()
      .focus();

    habilitarDesactivarSelect("tipo_cambio", false);
    habilitarDesactivarSelect("moneda", false);

    poner_totales_cant();
    // descuento_global();
  }

  function habilitarDesactivarSelect(select_name, activar = true) {
    let select = $("[name=" + select_name + "]");

    if (select.length) {
      activar ? select.removeAttr('disabled') : select.attr('disabled', 'disabled');
    }
    else {
      // console.log("activar elemento");
      activar ? $("." + select_name).removeClass('disabled') : $("." + select_name).addClass('disabled')
    }
  }

  function verificar_producto_unidad(fromTable = true) {
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

  // function ajaxs( data , url , funcs = {} )
  // {
  //   funcs.mientras ? funcs.mientras() : null;
  //   $.ajax({
  //     type : 'post',
  //     url  : url,  
  //     data : data,
  //     success : function(data){   
  //       funcs.success ? funcs.success(data) : defaultSuccessAjaxFunc(data);
  //     },
  //     error : function(data){
  //       funcs.error ? funcs.error(data) : defaultErrorAjaxFunc(data);       
  //     },
  //     complete : function(data){
  //       // console.log("ajax terminado");
  //       executing_ajax = false;        
  //       funcs.complete ? funcs.complete(data) : null;
  //     }
  //   });  
  // };

  function poner_codigo_documento() {
    $("[name=serie_documento] , [name=ref_serie]").parent('.form-group').removeClass('has-error');
    let nro_codigo = $("[name=serie_documento] option:selected").attr('data-codigo');
    $("[name=nro_documento]").val(nro_codigo);
  }


  function show_modal(modal = "#modalAccion", action = "show", backdrop = true) {
    $(modal).modal(action);
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
    show_modal("#modalSelectCliente", "show");
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


  // function poner_data_inputs( 
  //   data ,
  //   no_adicional = true ,
  //   adicional = null ,
  //   name_busqueda = "data-namedb"
  // ) {  
  //   for( prop in data ){  

  //     if( no_adicional !== true && no_adicional[prop]  ){
  //       no_adicional[prop]( data , adicional );
  //     }

  //     else {
  //       let ele = $( "[" +name_busqueda + "=" + prop + "]");        
  //       $("[" + name_busqueda + "=" + prop + "]").val( data[prop] );
  //     }

  //   }
  // }

  // function poner_data_inputs(
  //   data,
  //   no_adicional = true,
  //   adicional = null,
  //   name_busqueda = "data-namedb"
  // ) {
  //   console.log("poner_data_inputs", data);

  //   for (prop in data) {

  //     if (no_adicional !== true && no_adicional[prop]) {
  //       no_adicional[prop](data, adicional);
  //     }

  //     else {
  //       let ele = $("[" + name_busqueda + "=" + prop + "]");
  //       let val = data[prop];
  //       if (ele.is('[type=checkbox]')) {
  //         ele.prop('checked', Boolean(Number(val)))
  //       }
  //       else {
  //         ele.val(val);
  //       }
  //     }

  //   }
  // }



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

    console.log("es nota", is_nota('debito'));

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

    poner_codigo_documento();
  }

  function poner_data_cliente(data) {
    let funcs = {
      'tipo_documento_c': poner_tipo_cliente,
    }

    window.poner_data_inputs(data, funcs);
    show_modal(modal_cliente, "hide");
    nextFocus("cliente_documento");
  }

  function poner_tipo_cliente(data) {
    let tipo = data.tipo_documento_c.TdocNomb;
    $("[name=tipo_documento_c]").val(tipo);
    // console.log( " poner_El tip ode cliente" , data );
  }


  function poner_unidades(data, producto = null) {
    console.log("producto", producto)

    let unidades = null;
    let includeListNombre = true;

    if (data.unidades) {
      includeListNombre = false;
      unidades = data.unidades;
    }
    else {
      console.log("aqui no estoy");
      unidades = data.producto.unidades;
    }

    const $productoUnidad = $("[name=producto_unidad]");

    $productoUnidad.empty();

    for (let i = 0; i < unidades.length; i++) {

      const unit = unidades[i];
      let nombreUnidad = unit.UniAbre;

      // if (!includeListNombre) {
      //   nombreUnidad = unit.UniAbre + ' - ' + unit.lista.LisNomb;
      // }

      console.log(includeListNombre, nombreUnidad)

      let option =
        $("<option></option>")
          .attr('value', unit.Unicodi)
          .text(nombreUnidad);

      if (producto != null) {
        if (producto.UniCodi == unit.Unicodi) {
          option.attr('selected', 'selected')
          const unitPrecio = $("[name=moneda] option:selected").val() == '01' ? unit.UniPMVS : unit.UniPMVD;
          $("[name=producto_precio]").attr('data-default', unitPrecio)
        }
      }

      $productoUnidad.append(option)
    }
  }


  function set_precio() {
    let codigo_moneda, unidades;

    if (current_product_data == null) {
      return;
    }

    if (action_i() == "create") {
      console.log("action_i() create", current_product_data)

      codigo_moneda = current_product_data.producto.moncodi;
      unidades = current_product_data.unidades;
    }
    else {
      console.log("action_i() edit", current_product_data)
      codigo_moneda =
        $("[name=moneda] option:selected").val();
      unidades = current_product_data.unidades || current_product_data.Unidades;
    }

    let precio;
    let moneda = $("[name=moneda] option[value=" + codigo_moneda + "]").text();
    let producto_unidad = $("[name=producto_unidad] option:selected");
    let unidad_select = null;

    // console.log("unidades", current_product_data, unidades);

    for (let i = 0; i < unidades.length; i++) {
      if (unidades[i].Unicodi == producto_unidad.val()) {
        unidad_select = unidades[i];
        break;
      }
    }

    precio = window.isSoles ? unidad_select.UNIPUVS : unidad_select.UniPUVD;
    let precio_min = window.isSoles ? unidad_select.UniPMVS : unidad_select.UniPMVD;
    const decimales = window.isSoles ? window.decimales_soles : window.decimales_dolares;

    $("[name=producto_precio]").val(fixedNumber(precio, false, decimales));
    $("[name=producto_precio]")
      .attr('data-default', fixedNumber(precio_min, false, decimales));

    inputPrecioActiveInactive()

    calcular_importe();
  }

  function dcto_defecto(data) {
    if (data.ProDct1 === null || data.ProDct1 === "") {
      $("[name=producto_dct]").val(0);
    }

  }

  function poner_data_producto(data) {
    show_modal("#modalSelectProducto", "hide");
    data.producto.ProUnidades = null;
    $("[name=producto_cantidad]").val(1);
    let funcs_agregar = {
      "ProUnidades": poner_unidades,
      "ProDcto1": dcto_defecto,
    };
    current_product_data = data;

    window.poner_data_inputs(data.producto, funcs_agregar);
    let incluyeIGV = data.producto.incluye_igv == "1";
    $("[name=incluye_igv]").prop('checked', incluyeIGV);
    $("[name=icbper]").val(data.producto.icbper);

    nextFocus("producto_nombre");
    set_precio();
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


  function buscar_producto(id) {

    let data = {
      codigo: id,
    };
    let funcs = {
      success: poner_data_producto,
      error: function (data) {
        console.log("error buscar_producto 991", data);
      },
    };
    ajaxs(data, url_buscar_producto_datos, funcs);
  }



  function enter_table_ele(modal) {

    if (enter_accion) {
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
        return false;
      }

      else if (modal.is("#modalSelectProducto")) {

        buscar_producto(trSelect.find("td:eq(0)").text());

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

      $(dom).prop('disabled', false);

    });

    div_seccion_opuesta.find("select,input").not('.disabledFijo').each(function (index, dom) {

      $(dom).prop('disabled', true);
    });

  }

  function error_buscando_cliente(data) {
    cleanInputsGroup();

    $("#modalNuevoCliente").modal("show");
    document.getElementById("nuevocliente").focus();
    document.getElementById("nuevocliente").focus();
    let btn_nuevo_cliente = $("#nuevocliente");

    let documento_numero = $("[name=cliente_documento]").val();
    let href = btn_nuevo_cliente.data('url').replace('XXX', documento_numero)
    btn_nuevo_cliente.attr('href', href);
  }

  function accionar_buscar_cliente(e) {
    let id_documento = $("[name=cliente_documento]").val();

    if (e.keyCode === 13 || e.type == "blur") {

      if (id_documento.trim().length) {

        if (!isNaN(Number(id_documento)) || id_documento == ".") {

          let data = {
            codigo: id_documento,
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
    if (e) {
      if (e.keyCode === 13) return false;
    }
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
    show_select_gratuito();

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

  // serch pro wraning
  function accionar_buscar_producto(e) {
    if (e.keyCode === 13 || e.keyCode === undefined) {

      $isInputCode = $(this).is('[name=producto_codigo]')

      let producto_value_search = $(this).val();

      $isInputCode ?
        $(".select-field-producto").find("option[value=codigo]").prop('selected', true) :
        $(".select-field-producto").find("option[value=nombre]").prop('selected', true);

      show_modal("#modalSelectProducto", "show");
      table_productos.search(producto_value_search).draw();
    }

    // e.stopPropagation();
  }

  function importe() {
    return fixedValue(
      $("[name=producto_cantidad]").val() * $("[name=producto_precio]").val()
    );
  }

  function calcular_importe() {
    let data = {
      DetPrec: Number($("[name=producto_precio]").val()),
      // Cantidad
      DetCant: Number($("[name=producto_cantidad]").val()),
      // Porcentaje de descuento
      DetDcto: Number($("[name=producto_dct]").val()),
      // Porcentaje de ISC
      DetISC: Number($("[name=producto_isc]").val()),
      // Incluye IGV
      incluye_igv: $("[name=incluye_igv]").is(':checked'),
      // Bolsa @TODO
      icbper: $("[name=icbper]").val(),
      // Base
      DetBase: $("[name=producto_igv] option:selected").val().toLowerCase()
    }

    let calculo_result = calculosItem(data);
    $("[name=producto_importe]").val(fixedValue(calculo_result.total));
  }


  function calculosItem(data) {
    // let incluye_igv = data.incluye_igv;
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
      iscPorc: Number(data.DetISC),
      // Valor unitario inicial con IGV
      valorUnitarioXItem: Number(data.DetPrec),
      // Valor unitario inicial con ISC (valorUnitarioXItem / Porcentaje ISC)
      valorUnitarioXItemISC: 0, // Number(data.DetPrec),
      // Valor ISC Por Unidad (Cuando es el ISC por una unidad de producto)
      valorISCXUnidad: 0,
      // ISC Total
      valorISCTotal: 0,
      // Si el item tiene afectacion de la bolsa
      icbper: Number(data.icbper),
      // Si el item tiene afectacion de la bolsa
      icbperTotal: 0,
      // Valor de venta bruto  = (valorUnitarioXItem * cantidad)
      valorVentaBruto: 0,
      // Descuento total
      descuentoTotal: 0,
      // Valor de venta bruto = (valorVentaBruto - descuentoTotal)
      valorVentaDescuento: 0,
      // IGV Total de la operacio,
      igv: 0,
    }

    // let icbper_unit = 0.20;
    let incluye_igv = Number(data.incluye_igv)

    if (isGravada) {
      info.valorUnitarioXItem = incluye_igv ? (info.valorUnitarioXItem / IGV_VALUE) : info.valorUnitarioXItem;
    }


    // Si tiene isc a el valor unitario habra que aplicarle el isc
    // if (info.iscPorc) {
    //   let isc_factor = Number("1." + info.iscPorc);
    //   info.valorUnitarioXItem = info.valorUnitarioXItem / isc_factor;
    //   info.valorISCXUnidad = (info.valorUnitarioXItem / 100) * info.iscPorc;
    //   info.valorISCTotal = info.valorISCXUnidad * info.cantidad;
    // }

    // Valor de venta Bruto
    info.valorVentaBruto = info.valorUnitarioXItem * info.cantidad;

    // Si tiene descuento, calculador y aplicarselo al valor bruto
    if (info.dctoPorc) {
      info.descuentoTotal = (info.valorVentaBruto / 100) * info.dctoPorc;
      info.valorVentaBruto -= info.descuentoTotal;
    }


    info.valorISCTotal = info.iscPorc ? (info.valorVentaBruto * Number('0.' + info.iscPorc)) : 0;

    // Calcular IGV    
    if (isGravada) {
      info.igv = ((info.valorVentaBruto + info.valorISCTotal) / 100) * IGV_PORCENTAJE;
    }

    // Bolsa
    info.icbperTotal = info.icbper ? (icbper_unit) * info.cantidad : 0;

    // Calcular total
    let base = info.valorVentaBruto;
    info.valorVentaBruto = info.valorVentaBruto + info.valorISCTotal + info.igv + info.icbperTotal;

    let infoReturn = {
      total: info.valorVentaBruto,
      cantidad: info.cantidad,
      descuentoTotal: info.descuentoTotal,
      baseImponible: base,
      baseImposibleIGV: info.valorVentaDescuento + info.igv,
      igv: info.igv,
      isc: info.valorISCTotal,
      icbper: info.icbperTotal,
    };
    return infoReturn;
  }

  function fixedValue(value_importe) {
    if (value_importe == undefined || typeof value_importe == "object") {
      return "0";
    }
    value = typeof value_importe == "string" ? Number(value_importe) : value_importe;
    return value.toFixed(2);
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

    // console.log("i,d", importe() , descuento() );

    $importe.val(fixedValue(importe() - descuento()));
  }

  function moneda_precio_change() {
    set_precio();
    setMoneda();
  }


  function setMoneda() {
    window.isSoles = Number($("[name=moneda] option:selected").attr('data-esSol'));
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
        return false;
      }
    }

    // Enter
    else if (keyCode === 13) {
      if (modalIsOpen) {
        enter_table_ele(modalUp);
        return false;
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

  function eliminar_item(e) {
    e.preventDefault();
    if (confirm("Esta seguro que desea eliminar este item?")) {
      $(this).parents('tr').hide(1000, function () {
        $(this).remove();
        if (action_i() != "create") {
          cleanInputsGroup("producto", quitar_unidad);
        }
        action_i("create");
        poner_totales_cant();
      });
    }
  }


  function modificar_item(e) {
    e.preventDefault();

    let $this = $(this).parents("tr");
    let tr_selec = $this.parents("table").find(".seleccionando");
    let data = false;

    cleanInputsGroup("producto", quitar_unidad);

    if (tr_selec.length) {

      if ($this.is(tr_selec)) {
        $this.removeClass('seleccionando');
        habilitarDesactivarSelect("eliminar_item", true);
        action_i("create");
      }
      else {
        habilitarDesactivarSelect("eliminar_item", false);
        tr_selec.removeClass('seleccionando');
        $this.addClass("seleccionando");
        data = $this.data('info');
        action_i("edit");
      }
    }

    else {
      $this.addClass('seleccionando');
      habilitarDesactivarSelect("eliminar_item", false);
      data = $this.data('info');
      action_i("edit");
    }

    if (data) {
      current_product_data = data;
      let unidades = { unidades: JSON.parse($this.attr('data-unidades')) };
      poner_unidades(unidades, data);
      window.poner_data_inputs(data, true, null, 'data-name_item');
    }

    inputPrecioActiveInactive();
  }

  function inputPrecioActiveInactive() {
    let $inputPrecio = $("[name=producto_precio]");
    let precio = Number($inputPrecio.val())

    if (precio) {
      if (canModifyPrecios) {
        $inputPrecio.removeAttr('readonly');
      }
      else {
        $inputPrecio.attr('readonly', 'readonly');
      }
    }
    else {
      $inputPrecio.removeAttr('readonly');
    }

  }



  function show_comment_div(e = null) {
    if (e = null) {
      e.preventDefault();
    }

    // console.log("comentario div");
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

    var tipo_documento = $("[name=tipo_documento] option:selected");

    if (tipo_documento.text().toLowerCase().indexOf("boleta") == -1) {
      if (!verifyInputCliente()) {
        notiYFocus("cliente_documento", "Tiene que introducir el numero del documento del cliente");
        return;
      }
    }

    // RUC = TODO MENOS BOLETA
    // . = SOLO BOLETA
    // DNI NOTA CREDITO O NOTA DE DEBITO    

    // tipo documento cliente
    let tdc = $("[name=tipo_documento_c]").val();

    // tipo documento factura
    let tdf = $("[name=tipo_documento] option:selected").val();

    if (tdc == "RUC" && tdf == "03") {
      notiYFocus("tipo_documento", "El cliente con RUC, no puede hacer una compra con boleta");
      return;
    }

    if (tdc == "Ninguno" && tdf != "03") {
      notiYFocus("tipo_documento", "Con este cliente solo puede registrar boletas");
      return;
    }

    if ((tdc == "DNI" || tdc == "CARNET DE EXTRANGERIA" || tdc == "PASAPORTE") && tdf == "01") {
      notiYFocus("tipo_documento", "No tiene RUC el cliente para facturar");
      return;
    }


    if (!$("#table-items tbody tr").length) {
      notiYFocus("producto_nombre", "Tiene que introducir al menos un producto");
      return;
    }

    if (tdf == '01' && tdc !== 'RUC') {
      // if (tdf == '01' && tdc !== 'RUC' $('[name=cliente_documento]').val().length != 11 ){
      notiYFocus("cliente_documento", "La factura solo tiene que ser con RUC");
      return;
    }

    if (confirm("Esta seguro de guardar?")) {
      aceptar_guardado()
    }

  }


  function cotizacion_saved(data) {
    notificaciones("Guardado satisfactoriamente", "success");
    go_listado();
  }


  function guardar_factura(data) {
    return;
  }


  function error_guardar_factura(data) {
    let response = data.responseJSON;

    $(".div_esperando").hide();
    $(".div_guardar").show();

    if (typeof response == "string") {
      notificaciones(response, 'error');
    }
    else {
      defaultErrorAjaxFunc(data);
    }
  }

  function modal_guardar() {
    show_modal("#modalDeudas", "hide")
    show_modal("#modalGuardarFactura", "show", "static")
  }

  function show_hide_adicional_info() {
    $(".info_adicional").toggle();
  }

  function aceptar_guardado() {
    let items = [];

    $("#table-items .tr_item").each(function (i, d) {
      let attr = $(this).attr('data-info');
      console.log(i, attr);
      let info = JSON.parse($(this).attr('data-info'));



      console.log("info", info);

      let item_data = {
        'UniCodi': info.UniCodi,
        'DetCodi': info.DetCodi,
        'DetNomb': info.DetNomb,
        'DetCant': info.DetCant,
        'DetPrec': info.DetPrec,
        'DetDeta': info.DetDeta,
        'DetDcto': info.DetDcto,
        'DetImpo': info.DetImpo,
        'DetBase': info.DetBase,
        'incluye_igv': info.incluye_igv,
      }

      items.push(item_data);
    });

    // console.log("items", items );

    let data = {
      id_cotizacion: $("[name=codigo_venta]").val(),
      tipo: $("[name=tipo]").val(),
      import_id: $("[name=import_id]").val(),
      tipo_documento: $("[name=tipo_documento]").val(),
      nro_documento: $("[name=nro_documento]").val(),
      cliente_documento: $("[name=cliente_documento]").val(),
      cliente_nombre: $("[name=cliente_nombre]").val(),
      moneda: $("[name=moneda]").val(),
      contacto: $("[name=contacto]").val(),
      tipo_cambio: $("[name=tipo_cambio]").val(),
      forma_pago: $("[name=forma_pago]").val(),
      fecha_emision: $("[name=fecha_emision]").val(),
      fecha_vencimiento: $("[name=fecha_vencimiento]").val(),
      observacion: $("[name=observacion]").val(),
      Vtabase: $('[data-name=gravadas]').val(),
      VtaDcto: $('[data-name=descuento]').val(),
      VtaInaf: $('[data-name=inafectas]').val(),
      VtaExon: $('[data-name=exoneradas]').val(),
      VtaGrat: $('[data-name=gratuitas]').val(),
      VtaISC: $('[data-name=isc]').val(),
      VtaIGVV: $('[data-name=igv]').val(),
      total_cantidad: $("[name=cantidad_total]").val(),
      total_peso: $("[name=peso_total]").val(),
      total_importe: $("[name=total_importe]").val(),
      vendedor: $("[name=vendedor]").val(),
      nro_pedido: $("[name=nro_pedido]").val(),
      doc_ref: $("[name=doc_ref]").val(),
      tipo_guardado: $("[name=tipo_guardado]:checked").val(),
      items: items,
    }

    let funcs = {
      success: cotizacion_saved,
      error: error_guardar_factura,
      complete: function () {
        $("#aceptar_guardado").removeClass('disabled');
      }
    }

    tipo_guardado = $("[name=tipo_guardado]:checked").val();

    $(".div_guardar").hide();
    $(".div_esperando").show();


    let url = edicion ? url_editar_cotizacion : url_guardar_cotizacion;
    console.log("edicion", edicion, url);

    ajaxs(data, url, funcs);
  }

  function desactivarArea() {
    let option = $("[data-name=TipPago] option:selected");

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

  function go_listado() {
    window.location.href = $("#salir_").data('href')
  }

  function guardar_guia_salida() {
    if (confirm("Esta seguro que desea confirmar la guia de salida")) {

      let funcs = {
        success: go_listado
      };
      let data = {
        'id_almacen': $("[name=almacen_id]").val(),
        'id_movimiento': $("[name=tipo_movimiento]").val(),
        'id_factura': $("[name=codigo_venta]").val(),
      };

      ajaxs(data, url_save_guiasalida, funcs)

      console.log("aceptar guia de salida");
    }

    else {
      go_listado();
      console.log("no tener una guia de salida");
    }
  }


  function headerAjax() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  }

  function initialFocus() {

    producto_input_focus
      .select()
      .focus();
  }

  // function initTooltip(){
  //   // $("[data-toggle=tooltip]").tooltip();
  // }

  function seleccionar_elemento(tr) {
    let table = tr.parents("table");
    let tr_select = table.find("tr.select");

    if (tr_select.length) {
      tr_select.removeClass("select");
    }

    tr.addClass("select");
  }

  function seleccion_elemento() {
    console.log("seleccion de elemento", this);
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

    if ($t.is('.crear')) {
      agregar_item();
    }

    else if ($t.is('.modificar')) {
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

      // console.log("res desc", res);

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
    let target = $(this).data('target');
    let ele = $("[data-element=" + target + "]");

    // console.log("target y ele" , target , ele );
    // if( ele.is('.hide') ){
    //   ele.removeClass('hide')
    // }
    // else {
    //   ele.addClass('hide')
    // }
    ele.toggle();
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

  function mostrar_condi_venta() {
    $("#modalCondicionVenta").modal(true);
  }

  function guardar_condicion() {

    // 
    const type = $("#modalCondicionVenta").attr('data-condicion');

    let data = {
      type: type,
      descripcion: $("[name=condicion_venta]").val()
    };

    let funcs = {
      success: function () {
        $("#modalCondicionVenta").modal('hide');
        // show_modal("hide", "#modalCondicionVenta");
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
    console.log("Corre enviado exitosamente", data);
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
          console.log("error, ", data)
          activar_desactivar_email(true);
          notificaciones("Su correo no pude enviase", "error");

        },
      };

      ajaxs(data, url_enviar_email, funcs);

    }
  }




  function getTrSelect() {
    let tr_select = $("#modalPagos .select");
    console.log("tr_seleccionado", tr_select);
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


  function quitar_estilos() {
    $("#datatable-factura_select").removeAttr('style');
  }

  function buscar() {

    console.log("buscar producto");

    let id = $("[name=producto_codigo]").val();

    if (isNaN(id) || !id.trim().length) {
      return;
    }

    buscar_producto($("[name=producto_codigo]").val());
  }

  function open_modal_cliente() {
    $("#modalCliente").modal();
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

  function formatNumber(value) {
    return fixedNumber(value);
  }

  // seleccionar una opcion por defecto al select
  function select_value(select_name, value) {
    let select = $("[name=" + select_name + "]");
    select.find('opcion').prop('selected', false);
    select.find("option[value=" + value + "]").prop('selected', true)
  }

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
        // dont way sleep, iready hear--wghjyrtry------
        // "production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js"
        // let formData = new FormData();
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
    console.log("columna del indice", columns)
    for (let index = 0; index < window.columns_alms_hide.length; index++) {
      var column = table_productos.column(window.columns_alms_hide[index]);
      column.visible(!column.visible());
    }
  }



  function events() {

    $("[name=grupo_filter]").on('change', consultar_grupo_filter);

    $("body").on('click', '.ver-mostrar-almacenes', showHideAlmacenes)

    $("[name=familia_filter]").on('change', () => {
      table_productos.draw()
    });


    $("body").on('change', '.select-field-producto', function (E) {
      table_productos.draw();
    });

    $(".newCliente").on('click', open_modal_cliente);

    $("[name=tipo]").on('change', function () {

      let idnuevo = $('option:selected', this).data('nid');
      // console.log("idnuevo" , idnuevo);
      $("[name=codigo_venta]").val(idnuevo)

    });


    $('#cliente_documento').on('select2:close', function (data) {
      producto_input_focus
        .select()
        .focus();
    });

    $('#cliente_documento').on('select2:selecting', function (data) {
      let contacto = data.params.args.data.data.PCCont;
      console.log("data del cliente", data);
      console.log(data.params.args.data.data.tipo_documento_c.TdocNomb)

      $("[name=tipo_documento_c]").val(data.params.args.data.data.tipo_documento_c.TdocNomb);
      $("[name=contacto]").val(contacto);
    });


    $(".liberar").on('click', function () {
      if ($(this).is('disabled')) {
        return
      }

      ajaxs(
        { id_cotizacion: $("[name=codigo_venta]").val() },
        url_liberar,
        {
          success: function (data) {
            notificaciones("Cotizacion liberada", "success");
            $(".liberar").attr('disabled', 'disabled');
            $(".estado").text('LIBERADA');
            // console.log("liberado");
          }
        }
      )

    });

    $("[name=cliente_documento]").on('blur', accionar_buscar_cliente)

    $("[name=producto_codigo]").on('blur', buscar)

    $(".modal").on('hidden.bs.modal', function () {
      enter_accion = false;
    })

    $(".info_principal").on('click', function () {

      $(".info_adicional").toggle();

    });


    $("#modalSelectProducto").on('shown.bs.modal', function (e) {

      let table_id = $("table", this).attr('id')
      let select = "#" + table_id + "_filter input";

      $(select).focus();

    })

    $(".modal").on('show.bs.modal', function (e) {

      // console.log("modal Cerrandose", enter_accion );
      if (enter_accion) {
        e.preventDefault();
        return
      }

    })


    $(".enviar_mail").on('click', confirm_enviar_mail);

    $(".guardar_condicion").on('click', guardar_condicion);

    $(".condi_venta").on('click', mostrar_condi_venta);

    $(".open-data").on('click', ver_data_cliente)

    $("[name=descuento_global]").on('keyup', descuento_global)

    $("[name=producto_dct]").on('keyup', calcular_descuento)

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


    $("#datatable-productos,#datatable-clientes,#table_pagos,#datatable-clientes, #datatable-factura_select").on('click', "tbody tr", seleccion_elemento);

    $("#datatable-productos,#datatable-clientes,#table_pagos-pagos,#datatable-factura_select").on('dblclick', "tbody tr", enter_table_ele_click);


    $(".elegir_elemento").on('click', enter_table_ele_click)

    $("*").on("keydown", teclado_acciones);

    $("[name=cliente_documento]").on("keydown", accionar_buscar_cliente);

    $("[name=producto_codigo] , [name=producto_nombre]").on("keydown", accionar_buscar_producto);

    $("#boton_buscar").on("click", accionar_buscar_producto);

    $("[name=producto_nombre]").on("dblclick", accionar_buscar_producto);

    // cambiar de foco carefull
    $("[name=fecha_emision],[name=fecha_referencia],[name=moneda],[name=tipo_cambio],[name=forma_pago],[name=producto_unidad],[name=producto_cantidad],[name=producto_precio],[name=ref_documento],[name=ref_serie],[name=ref_numero],[name=ref_fecha], [name=producto_isc],[name=producto_isc_other],[name=producto_percepcion],[name=producto_dct] ").on("keydown", cambiar_focos);

    $("[name=soles],[name=dolar],[name=VtaTcam]", ".calculadora").on('keyup', calculadora_actions);

    $("[data-name=TipPago]").on('change', desactivarArea);

    $("#guardarFactura").on('click', verificar_data_factura);

    $("#aceptar_guardado").on('click', aceptar_guardado);

    $(".agregar_comentario").on('click', show_comment_div);

    $("table").on('click', '.eliminar_item', eliminar_item);

    $("#table-items").on('click', ".modificar_item", modificar_item);

    $("[name=moneda]").on("change", moneda_precio_change);

    $("[name=producto_unidad]").on("change", set_precio);

    $("[name=producto_cantidad] , [name=producto_precio] , [name=producto_dct], [name=producto_isc]").on("keyup", calcular_importe);
    $("[name=incluye_igv]").on("change", calcular_importe);

    $("[name=producto_dct]").on("keyup", calcular_descuento);

    $("[name=producto_igv]").on("change", calcular_igv);

    $("[name=forma_pago]").on("change", agregar_dias);

    $("[name=producto_percepcion]").on("keyup", calcular_porcentaje);


    // tipo de documento
    $("[name=tipo_documento]").on("change", cambiar_tipo_documento);

    // serie documento
    $("[name=serie_documento]").on("change", poner_codigo_documento);
  }

  function initDatatables() {

    // function dataUnidadPrincipal(value, type, data, settings) {
    //   let dataUnidadFirst = data.unidades_[0];
    //   let valOutput = '';
    //   let columnsProperty = {
    //     4: 'UniPUCD', 5: 'UniPUCS', 6: 'UniMarg', 7: 'UNIPUVS',
    //   }
    //   if (dataUnidadFirst) {
    //     valOutput = dataUnidadFirst[columnsProperty[settings.col]];
    //   };
    //   return fixedNumber(valOutput);
    // }

    function dataUnidadPrincipal(value, type, data, settings) {

      let dataUnidadFirst = data.unidades_[0];
      let valOutput = '';

      // let columnsProperty = {
      //   4: { name: 'UniPUCD', decimales: window.decimales_dolares },
      //   5: { name: 'UniPUCS', decimales: window.decimales_soles },
      //   6: { name: 'UniMarg', decimales: 2 },
      //   7: { name: 'UNIPUVS', decimales: window.decimales_soles },
      // }

      let columnsProperty;
      if (this.ver_costos) {
        columnsProperty = {
          4: { name: 'UniPUCD', decimales: window.decimales_dolares },
          5: { name: 'UniPUCS', decimales: window.decimales_soles },
          6: { name: 'UniMarg', decimales: 2 },
          7: { name: window.isSoles ? 'UNIPUVS' : 'UNIPUVD', decimales: window.isSoles ? window.decimales_soles : decimales_dolares },
        }
      }
      else {
        columnsProperty = {
          4: { name: window.isSoles ? 'UNIPUVS' : 'UNIPUVD', decimales: window.isSoles ? window.decimales_soles : decimales_dolares },
        }
      }

      if (dataUnidadFirst) {
        valOutput = dataUnidadFirst[columnsProperty[settings.col].name];
      }

      return fixedNumber(valOutput, false, columnsProperty[settings.col].decimales);
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

    $("#datatable-productos").one("preInit.dt", function () {
      $button = $("<select class='select-field-producto input-sm form-control'><option value='codigo'>Codigo</option> <option value='nombre'>Nombre</option> </select>");
      $("#datatable-productos_filter label").prepend($button);
      $button.button();
    });

    let product_columns = [
      { data: 'ProCodi', searchable: false },
      { data: 'unpcodi', searchable: false },
      { data: 'ProNomb', className: 'nombre_producto', searchable: false },
      { data: 'marca_.MarNomb', searchable: false },
    ]

    const ver_costos = Number($("#datatable-productos").attr("data-costos"));

    if (ver_costos) {
      product_columns.push({ data: 'ProMarg', className: 'text-right', searchable: false, render: dataUnidadPrincipal.bind({ ver_costos: ver_costos }) });
      product_columns.push({ data: 'ProPUCD', className: 'text-right', searchable: false, render: dataUnidadPrincipal.bind({ ver_costos: ver_costos }) });
      product_columns.push({ data: 'ProPUCS', className: 'text-right', searchable: false, render: dataUnidadPrincipal.bind({ ver_costos: ver_costos }) });
    }

    product_columns.push({ data: 'ProPUVS', className: 'text-right', searchable: false, render: dataUnidadPrincipal.bind({ ver_costos: ver_costos }) });

    product_columns.push({ data: 'ProPUVS', className: 'text-right total-almacen almacen-id-total', searchable: false, render: sumStock });

    // 
    let cantidad_almacenes = $("#datatable-productos thead td.almacenes");
    for (let index = 0; index < cantidad_almacenes.length; index++) {
      let stock_number = $(cantidad_almacenes[index]).attr('data-id');
      let campo_id = 'prosto' + stock_number;
      product_columns.push({ data: campo_id, className: 'text-right ' + 'almacen-id-' + stock_number, searchable: false });
    }

    product_columns.push({ data: 'prosto10', className: 'text-right', searchable: false });
    product_columns.push({ data: 'ProPeso', className: 'text-right', searchable: false, formatNumber });
    product_columns.push({ data: 'BaseIGV', className: 'text-right', searchable: false });
    product_columns.push({ data: 'ISC', className: 'text-right', searchable: false });
    product_columns.push({ data: 'tiecodi', searchable: false });

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
      "columns": product_columns
    });

    table_productos.columns.adjust().draw();
  }


  function clearInputsInit() {
    if (create) {
      cleanInputsGroup('cliente');
      cleanInputsGroup('producto');
    }
    else {
      let v = $("[name=nro_documento]").val();
      $("[name=nro_documento]").attr('placeholder', v);
      $("[name=nro_documento]").val(v);
    }
  }

  function clearDataTable() {
    $("#datatable-factura_select").removeAttr('style');
    $("#datatable-productos thead td").removeAttr("style");
    $("#datatable-factura_select").removeAttr('style');
    $("#datatable-productos thead td").removeAttr("style");
  }


  function init() {
    headerAjax()
    $("[data-toggle=tooltip]").tooltip();
    initDatatables();
    events();
    clearInputsInit();
    poner_codigo_documento();
    date();
    clearDataTable();
    setProductoInputSearchDefaultFocus()
    initialFocus();
    setMoneda()
  };

  init();

});

Helper__.init();