window.producto_input_focus = null;

import { AppModalProducto } from "../productos/modal_producto.js";

window.datatableCotiExists = false;

function ver_pagos() {
  AppPagosIndex.init();
  AppPago.set_callback(AppPagosIndex.show_notopenmodal.bind(AppPagosIndex));
  AppPagosIndex.tipo_pago_default = $("[name=medio_pago]").attr('data-id');
  AppPagosIndex.set_id($("[name=CpaOperNext]").val());
  AppPagosIndex.show_openmodal();
}

function agregar_dias() {
  let dias = Number($("[name=concodi] option:selected").attr('data-dias'));
  let fecha_inicial = $("[name=CpaFCpa]").attr("data-fecha_inicial");
  let fecha_actual = $("[name=CpaFCpa]").val();

  console.log("fecha_inciial", fecha_inicial)

  if (dias == 0) {
    $("[name=CpaFven]").val(fecha_inicial);
    return;
  }

  else {
    let newDate = addDays(new Date(fecha_actual), dias + 1);
    $("[name=CpaFven]").datepicker("update", newDate);
  }
}


// Helper
let H = Helper__;

function initialFocus() {
  let ele = $(["name=CpaSerie"]);

  if (!ele.is('[readonly]')) {
    ele.focus();
  }
}

let App = {
  form_creating: true,
  product_selected: null,
  eles: {
    form_principal: $('.data-documento'),
    totales: $(".totales"),
    item_form: {
      form: $("#item-add form"),
    }
  }
};

App.eles.item_form.codigo = $("[name=ProCodi]", App.eles.item_form.form);
App.eles.item_form.nombre = $("[name=Detnomb]", App.eles.item_form.form);
App.eles.item_form.cantidad = $("[name=DetCant]", App.eles.item_form.form);
App.eles.item_form.precio = $("[name=DetPrec]", App.eles.item_form.form);
App.eles.item_form.dcto1 = $("[name=DetDct1]", App.eles.item_form.form);
App.eles.item_form.dcto2 = $("[name=DetDct2]", App.eles.item_form.form);
App.eles.item_form.importe = $("[name=DetImpo]", App.eles.item_form.form);
App.eles.item_form.unidad = $("[name=UniCodi]", App.eles.item_form.form);

function set_inputhidden_value() {
  return $("[name=moncodi]", ".form_principal").val($("[name=moncodi_] option:selected", ".form_principal").val());
}

function validateForm() {

  if (!$("#items-table tbody tr").length) {
    noti_focus("[name=Detnomb]", "Debe registrar al menos un producto")
    return false;
  }

  if (!$("[name=CpaSerie]").val()) {
    noti_focus("[name=CpaSerie]", "Debe registrar la serie")
    return false;
  }

  if (!$("[name=CpaNumee]").val()) {
    notificaciones("Debe registrar el numero del documento serie", "error");
    return false;
  }

  if (!$("[name=PCcodi]").val()) {
    $('[name=PCcodi]').select2('open')
    notificaciones("Debe seleccionar un cliente", "error");
    return false;
  }

  console.log("validateForm", true);
  return true;
}

function success_save(data) {
  notificaciones(data.message, "success");
  let url = $(".salir_button").attr('href');
  setTimeout(() => {
    window.location.href = url;
  }, 2000);
}

function save() {
  if (!validateForm()) {
    return false;
  }

  let data = {};
  let items = [];

  $("#load_screen").show();

  $("#items-table tbody tr").each(function (i, d) {
    let $tr = $(d);    
    let info = $tr.data('info');    
    const info_data = {
      'Detcodi' : info.Detcodi,
      'Detnomb' : info.Detnomb,
      'UniCodi' : info.UniCodi,
      'DetCant' : info.DetCant,
      'DetDct1' : info.DetDct1,
      'DetDct2' : info.DetDct2,
      'DetPrec' : info.DetPrec,
      'DetImpo' : info.DetImpo,
      'update_costo': Number($tr.find('.update_costo').is(':checked')),
    }
    items.push(info_data);
  });


  data = getFormData($("#form_principal"))
  data.IGVEsta = Number($("[name=incluye_igv]").is(':checked'));
  data.items = items;
  data.total = $("[data-total=Total]").val();
  data.igv_porcentaje = $("[name=igv_porcentaje] option:selected").attr('data-porc');
  let url = $("#form_principal").data('url');

  let funcs = {
    success: success_save, 
    complete: function (data) {
      $("#load_screen").hide();
    }
  }

  ajaxs(data, url, funcs);
}

function getIGV() {
  return Number($("[name=igv_porcentaje] option:selected").attr('data-porc'));
}


  function inputChangeIncluirIgv()
  {
    changeIncluyeIGV(false);
  }


function changeIncluyeIGV(noApplyIfNegative = true){

  const incluyeIGV = $("[name=incluye_igv]").is(':checked');
  
  if ( noApplyIfNegative ){
    if(incluyeIGV == false){
      return;
    }
  }

  const igvPorc = getIGV();

  const $igv = $(".total-igv");
  const $base = $(".total-base");
  const $total = $(".total-importe");

  let base_val = Number($base.val());
  let total_val = Number($total.val());

  const igvBaseUno = ((igvPorc / 100) + 1);

  if( incluyeIGV ){
    total_val = base_val;
    base_val = base_val / igvBaseUno;
  }
  else {
    base_val = total_val;
    total_val = base_val * igvBaseUno;
  }

  $total.val(fixedNumber(total_val));
  $base.val(fixedNumber(base_val));
  $igv.val(fixedNumber(total_val - base_val));
}


function calculosItem(precio, cantidad, descuento) {

  let info = {
    // Precio del producto
    precio: Number(precio),
    // Cantidad
    cantidad: Number(cantidad),
    // Porcentaje de descuento
    dctoPorc: Number(descuento),
    // Valor de venta por item    = valorVentaBruto - descuento   
    subTotal: 0,
    // Descuento total
    descuentoTotal: 0,
    // Valor de venta bruto = (valorVentaBruto - descuentoTotal)
    total: 0,
    // IGV Total de la operación,
    igv: 0,
  }

  // Valor de venta Bruto
  info.valorVentaBruto = info.precio * info.cantidad;
  info.valorVentaPorItem = info.valorVentaBruto;

  // Calcular el descuento 
  if (info.dctoPorc) {
    info.descuentoTotal = (info.valorVentaBruto / 100) * info.dctoPorc;
    info.valorVentaPorItem -= info.descuentoTotal;
  }

  // Calcular IGV    
  info.igv = (info.valorVentaPorItem / 100) * getIGV();

  info.total = info.valorVentaPorItem + info.igv;

  let infoReturn = {
    cantidad: info.cantidad,
    descuentoTotal: info.descuentoTotal,
    igv: info.igv,
    SubTotal: info.valorVentaPorItem,
    total: info.total,
  };

  return infoReturn;
}

function sum_cant() {
  let info = {
    Cantidad: 0,
    Peso: 0,
    IGV: 0,
    SubTotal: 0,
    Total: 0,
  };

  $("#items-table tbody tr").each(function (index, dom) {
    let data = $(this).data('info');

    let dcto = Number(data.DetDct1) + Number(data.DetDct2);
    let oper_resultados = calculosItem(data.DetPrec, data.DetCant, dcto);

    let unidad = data.producto.unidades_.filter(unidad => { return unidad.Unicodi == data.UniCodi })[0];
    info.Cantidad += oper_resultados.cantidad;
    info.Peso += Number(unidad.UniPeso) * info.Cantidad;
    info.Total += oper_resultados.total;
    info.SubTotal += oper_resultados.SubTotal;
    info.IGV += oper_resultados.igv;

  });

  return info;
}

function setFormItemAction(create = true) {
  App.form_creating = create;
}

function setFormItemCreateState() {
  setFormItemAction(true);
}

function setFormItemEditState() {
  setFormItemAction(false);
}


function select_item(e) {
  e.preventDefault();
  H.clean_form(App.eles.item_form.form, true);

  let $tr = $(this).parents('tr');
  let $tr_selected = $tr.parents("tbody").find(".selected");

  // si existe el tr para seleccionar
  if ($tr_selected.length) {

    if ($tr.is($tr_selected)) {
      $tr.removeClass('selected');
      setFormItemCreateState()
    }
    else {
      $tr_selected.removeClass('selected');
      $tr.addClass("selected");
      setFormItemEditState()
    }
  }

  else {
    $tr.addClass('selected');
    setFormItemEditState()
  }

  if (App.form_creating == false) {
    App.product_selected = $tr.data('info');
    H.set_data_form(App.eles.item_form.form, App.product_selected, false, false, 'fieldItem');
    set_value(App.eles.item_form.unidad, App.product_selected.UniCodi)
  }

  producto_input_focus
    .select()
    .focus();
}

function search_producto(value) {
  AppModalProducto.showSearch(value);
}

function search_bycodigo(value) {
  search_producto(App.eles.item_form.codigo.val());
}

function poner_totales_cant() {
  let info = sum_cant();
  for (let prop in info) {
    $("[data-total=" + prop + "]").val(fixedNumber(info[prop]));
  }
}

function calcular_importe() {
  App.eles.item_form.importe.val(fixedNumber(importe() - descuento()));
}

function importe() {
  return fixedNumber(App.eles.item_form.cantidad.val() * App.eles.item_form.precio.val());
}

function descuento() {
  let dcto_total = Number(App.eles.item_form.dcto1.val()) + Number(App.eles.item_form.dcto2.val())
  return fixedNumber((importe() / 100) * dcto_total);
}

function validatorItem() {
  if (!$("[name=Detcodi]").val()) {
    H.notiYFocus("Detcodi", "Ponga el codigo del producto")
    return false;
  }

  if (!App.eles.item_form.nombre.val()) {
    H.notiYFocus("Detnomb", "Ponga el nombre del producto")
    return false;
  }

  if (!App.eles.item_form.unidad.val()) {
    H.notiYFocus("UniCodi", "Es necesario la unidad")
    return false;
  }

  console.log(App.eles.item_form.cantidad.val(), App.eles.item_form.precio.val())

  if (!isNumber(App.eles.item_form.cantidad.val())) {
    H.notiYFocus("DetCant", "Introduzca la cantidad correctamente")
    return false;
  }

  if (!isNumber(App.eles.item_form.precio.val())) {
    H.notiYFocus("DetPrec", "Introduzca la el precio correctamente")
    return false;
  }

  if (!isNumber(App.eles.item_form.dcto1.val())) {
    H.notiYFocus("DetDct1", "Introduzca el descuento 1 correctamente")
    return false;
  }

  if (!isNumber(App.eles.item_form.dcto2.val())) {
    H.notiYFocus("DetDct2", "Introduzca el descuento 2 correctamente")
    return false;
  }

  return true;
}

function add_item(e = false, orden_pago = false) {

   if (e) { 
    e.preventDefault();
  }

  if( orden_pago == false ){
    if (validatorItem() == false) {
      return false;
    }
  }

  let data =  orden_pago ? orden_pago : getFormData(App.eles.item_form.form);

  if( orden_pago == false ){
    data.producto = App.form_creating ? App.product_selected : App.product_selected.producto;
  }
  
  let columns = [
    { name: 'Detcodi' },
    { name: 'Detcodi' },
    { name: 'producto.unpcodi' },
    { name: 'Detnomb' },
    { name: 'DetCant', search: false },
    { name: 'DetPrec', search: false },
    { name: 'DetDct1', search: false },
    { name: 'DetDct2', search: false },
    { name: 'DetImpo', search: false },
    { name: 'btns', defaultContent: "<a href='#' class='btn modificar_item btn-xs btn-primary'> <span class='fa fa-pencil'></span> </a><a href='#' class='btn eliminar_item btn-xs btn-danger'> <span class='fa fa-trash'></span> </a>" },
  ];

  if (App.form_creating) {
    columns.unshift({ name: 'cc', defaultContent: `<input type="checkbox" class="update_costo" value="1">` })
    add_to_table("#items-table", [data], columns, false, true);
  }
  else {
    let tr = $("#items-table tbody tr.selected");
    let inputCheckBox = tr.find('.update_costo').is(':checked') ?
      `<input type="checkbox" checked class="update_costo" value="1">` :
      `<input type="checkbox" class="update_costo" value="1">`;

    columns.unshift({ name: 'cc', defaultContent: inputCheckBox })

    let tds = create_tds(data, columns);
    add_to_tr(tr, tds, true, data);
    setFormItemCreateState();
  }

  H.clean_form(App.eles.item_form.form, true);

  producto_input_focus
    .select()
    .focus();

  poner_totales_cant();
  desactivarSelect();

  changeIncluyeIGV(true);

  $("#items-table tbody tr.selected").removeClass('selected');
}

function client_search() {
  $('[name=PCcodi]').select2('open')
}

function desactivarSelect() {
  let hasElements = $("#items-table tbody tr").length;
  toggleDisabledSelect('.form_principal [name=moncodi_]', hasElements);
  toggleDisabledSelect('.form_principal [name=igv_porcentaje]', hasElements);

  if (hasElements) {
    $('.form_principal [name=CpaTCam]', hasElements).attr('readonly', 'readonly')
    $('.form_principal [name=igv_porcentaje]', hasElements).attr('readonly', 'readonly')
  }
  else {
    $('.form_principal [name=CpaTCam]', hasElements).removeAttr('readonly');
    $('.form_principal [name=igv_porcentaje]', hasElements).removeAttr('readonly')
  }
}

function eliminar_item(e) {
  e.preventDefault();
  if (confirm("Esta seguro que desea eliminar este item?")) {
    let tr = $(this).parents('tr');
    tr.addClass('removing');
    if (App.form_creating == false) {
      H.clean_form(App.eles.item_form.form, true);
    }
    tr.hide(500, function () {
      tr.remove();
      poner_totales_cant();
      desactivarSelect();
      changeIncluyeIGV(true);
    })
  }
}

function redirectHome(data) {
  // console.log("Redirect" ,data );
  // return;
  location.href = $(".salir_button").attr('href');
}

function aceptar_guia() {
  let data =
  {
    serie: $("[name=serie]", "#modalGuiaSalida").val(),
    numero: $("[name=numero]", "#modalGuiaSalida").val(),
    fecha: $("[data-de]", "#modalGuiaSalida").val(),
    almacen: $("[name=almacen_id] option:selected", "#modalGuiaSalida").val(),
    no_mov: $("[name=no_mov]", "#modalGuiaSalida").is(':checked') ? 1 : 0,
  }

  let funcs = {
    success: redirectHome,
    complete : () => { 
      $("#load_screen").hide();
    }
  }

  let url = $("#modalGuiaSalida").data('url');
  $("#load_screen").show();
  ajaxs(data, url, funcs);
}

function getMoneda() {
  return $("[name=moncodi] option:selected").val();
}

function isMonedaSol() {
  return getMoneda() == "01";
}

function setPrecioUnidadEvent() {
  let unidades = App.form_creating ? App.product_selected.unidades_ : App.product_selected.producto.unidades_;
  setPrecioUnidad(unidades);
}

function setPrecioUnidad(unidades) {
  let productoUniCodi = $("option:selected", App.eles.item_form.unidad).val();
  let unidad = unidades.filter(unidad => { return unidad.Unicodi == productoUniCodi })[0];
  let $ele = App.eles.item_form.precio;
  isMonedaSol() ? $ele.val(unidad.UNIPUVS) : $ele.val(unidad.UniPUVD);
  calcular_importe();
}

function selectProducto(ele) {

  let selected = AppModalProducto.get_selected_row();
  if (!selected) {
    notificaciones('Selecciona un producto', "error");
    return;
  }

  let data = selected.data('info');
  data.ProNomb.replace('&quot', '');
  H.clean_form(App.eles.item_form.form);
  H.set_data_form(App.eles.item_form.form, data, false, false, 'fieldProducto');
  setPrecioUnidad(data.unidades_);
  App.product_selected = data;
  AppModalProducto.hide();
  H.nextFocus("UniCodi");
}

function setProductoInputSearchDefaultFocus() {

  let cursor_p = $("[name=Detcodi]").is('.focus') ? 0 : 1;


  if (cursor_p == "0") {
    producto_input_focus = $("[name=Detcodi]");
  }

  if (cursor_p == "1") {
    producto_input_focus = $("[name=Detnomb]");
  }

}


// eventos

function makeImport(e) {
  e.preventDefault();

  let $tableItems = $("#items-table");
  let $trSelected = $('#datatable-cotizacion_select tr.select');

  if ($trSelected.length) {

    if ($tableItems.find('tbody tr').length) {
      if (!confirm('Tienes productos agregados, al importar se borraran, estas deacuerdo?')) {
        return false;
      }
    }

    // cliente_documento

    // En las finanzas
    let data = $trSelected.data('info')
    //
    const $clienteDocumento = $("#cliente_documento");

    const tipos = {
      "0": "Ninguno",
      "1": "DNI",
      "4": "CARNET DE EXTRANGERIA",
      "6": "RUC",
      "7": "PASAPORTE",
      "B": "DOC.IDENT",
    }

    const text = data.cliente_with.PCRucc + ' - ' + data.cliente_with.PCNomb;
    $("[name=tipo_documento_c]").val(tipos[data.cliente_with.TDocCodi]);
    const $cliente_documento = $("#cliente_documento ");
    $cliente_documento.select2('destroy');
    $cliente_documento.empty();
    $clienteDocumento.attr('data-id', data.cliente_with.PCCodi);
    $clienteDocumento.attr('data-text', text);
    initSelect2("#cliente_documento");

    // Procesando importacion
    $(`[name=moncodi_] option[value=${data.moncodi}]`).prop('selected', true);
    $(`[name=concodi] option[value=${data.ConCodi}]`).prop('selected', true);
    $("[name=Docrefe]").val(data.CotNume);
    $(`[name=tipo_documento] option[value=${data.TidCodi}]`).prop('selected', true);

    // items-table
    let items = data.items;
    // let table = data.table;

    $tableItems.find('tbody').empty();

    for (let i = 0; i < items.length; i++) {
      const currentItem = items[i]
      let dataItem = {};

      dataItem.Detcodi = currentItem.DetCodi;
      dataItem.Detnomb = currentItem.DetNomb;
      dataItem.UniCodi = currentItem.UniCodi;
      dataItem.DetCant = currentItem.DetCant;
      dataItem.DetPrec = currentItem.DetPrec;
      dataItem.DetDct1 = currentItem.DetDcto;
      dataItem.DetDct2 = 0;
      dataItem.DetImpo = currentItem.DetImpo;
      dataItem.producto = currentItem.producto;
      dataItem.producto.unidades_ = currentItem.producto.unidades;
      add_item(false, dataItem );
    }

    $("#modalImportacion").modal('hide');
    return;
  }



  notificaciones('Tiene que Seleccionar una Orden de Compra', 'danger')
}

function open_modal_cliente() {
  $("#modalCliente").modal();
}

H.add_events(function () {


  $('#cliente_documento').on('select2:selecting', function (data) {
    $("[name=zona] option[value=" + data.params.args.data.data.ZonCodi + "] ").prop('selected', true)
    $("[name=vendedor] option[value=" + data.params.args.data.data.VenCodi + "] ").prop('selected', true)
  });


  $(".aceptar_importacion").on('click', makeImport)

  $("[name=incluye_igv]").on('change', inputChangeIncluirIgv )

  $("[name=CpaNumee],[name=CpaSerie],[name=serie],[name=numero]").on('blur', e => {
    const value = e.target.value;    
    if( value.length != 0 ){
      e.target.value = String(value).padStart(Number(e.target.getAttribute('maxlength')), 0);
    }
  });
  
  eventos_predeterminados('table_select_tr', '#datatable-cotizacion_select')

  H.setOrderFocus({
    'ProCodigo': search_bycodigo,
    'Detnomb': search_bycodigo,
    'UniCodi': 'DetCant',
    'CpaFCpa': 'CpaFCon',
    'CpaFCon': 'CpaFven',
    //
    'CpaFven': 'CpaFven',
    'CpaFCon': 'CpaFven',
    'CpaFven': 'moncodi',
    //
    'moncodi': 'VtaTcam',
    'VtaTcam': 'VenCodi',
    'VtaTcam': 'concodi',
    'concodi': 'VtaPedi',
    'VtaPedi': 'Docrefe',
    'concodi': 'VtaPedi',
    'VtaPedi': 'Cpaobse',
    'Cpaobse': 'Detnomb',
    'TidCodi': 'CpaSerie',
    'CpaSerie': 'CpaNumee',
    'CpaNumee': client_search,
    'DetCant': 'DetPrec',
    'Stock': 'DetPrec',
    'DetDct1': 'DetDct2',
    'DetDct2': add_item,
    'DetPrec': add_item
  });

  $('[name=PCcodi]').on('select2:close', function (data) {
    producto_input_focus
      .select()
      .focus();
  });

  $('#modal-importacion').on('click', (e) => {

    e.preventDefault();

    if (datatableCotiExists) {
      $("#modalImportacion").modal(true);
      return
    }

    datatableCotiExists = true;

    const $table = $("#datatable-cotizacion_select");
    const url = $table.attr('data-url');
    const local = $("#informacion_empresa-local option:selected").attr('data-id')
    // console.log({url});

    const columns = [
      { data: 'CotNume' },
      { data: 'CotFVta' },
      {
        data: 'CotNume', render: (data, display, info, settings) => {
          let tipoDocNombre = {
            '0': { fullName: 'Sin documento', nombre: 'S/D' },
            "1": { fullName: 'DNI (Documento Nacional de Identidad)', nombre: "DNI" },
            "4": { fullName: 'Carneta de Extranjeria', nombre: "C/E" },
            "6": { fullName: 'Registro Único de Contribuyente', nombre: "RUC" },
            "7": { fullName: 'PASAPORTE', nombre: "PASAPORTE" },
            "B": { fullName: 'Documento Identidad Nacional (Extranjeros)', nombre: "DOC.IDENT" },
          }
          const fullName = tipoDocNombre[info.cliente_with.TDocCodi].fullName;
          const nombre = tipoDocNombre[info.cliente_with.TDocCodi].nombre;
          const documento = info.cliente_with.PCRucc;
          const nombreCliente = info.cliente_with.PCNomb;
          const docHtml = `<div class="doc"><abbr title="${fullName}">${nombre}</abbr> <span class="documento"> ${documento} </span> </div>`;
          const $nombreCliente = `<div class="nombre">${nombreCliente}</div>`;
          return `<div class="proveedor-data">
        ${docHtml}
        ${$nombreCliente}
        </div>`
        }
      },
      {
        data: 'cotimpo', render: (data, display, info, settings) => {
          console.log("data", data)
          return `<div class="importe-oc">${info.moneda.monabre}${data}</div>`
        }
      },
      { data: 'usuario.usulogi' },
    ];

    $table.DataTable({
      "processing": true,
      "serverSide": true,
      "lengthChange": false,
      "ordering": false,
      "paging": true,
      "ajax": {
        "url": url,
        "data": function (d) {
          return $.extend({}, d, {
            "estado": 'P',
            "tipo": '99',
            'local': local,
            'withItems': true
          });
        }
      },
      "oLanguage": { "sSearch": "", "sLengthMenu": "_MENU_" },
      "initComplete": function initComplete(settings, json) {
        $('div.dataTables_filter input').attr('placeholder', 'Buscar');
      },
      createdRow: function (row, data, index) {
        $(row).data('info', data);
      },
      "columns": columns
    })

    $("#modalImportacion").modal(true);
  });

  AppModalProducto.init();
  AppModalProducto.add_event_btn(selectProducto)

  $("#boton_buscar").on('click', function () {

    let type = $('[name=Detcodi]').val().length ? 'codigo' : 'nombre';
    AppModalProducto.changeSearchSelect(type);

    let value = $('[name=Detcodi]').val().length ?
      $('[name=Detcodi]').val() :
      $('[name=Detnomb]').val();
    search_producto(value)
  });

  $(".keyjump").on('keyup', function (e) {
    if (e.keyCode == 13) {
      H.nextFocus($(this).attr('name'))
    }
  });

  $("[name=Detcodi],[name=Detnomb]").on('keyup', function (e) {

    if (e.keyCode == 13) {
      let type = $(this).is('[name=Detcodi]') ? 'codigo' : 'nombre';
      // console.log( type );
      AppModalProducto.changeSearchSelect(type);
      search_producto(this.value)
    }
  });

  $("[name=DetCant],[name=DetPrec],[name=DetDct1],[name=DetDct2]", App.eles.item_form.form).on("keyup", calcular_importe);

  App.eles.item_form.unidad.on("change", setPrecioUnidadEvent);

  $("#add-item", App.eles.item_form.form).on("click", add_item);
  $("#items-table").on('click', '.eliminar_item', eliminar_item);
  $("#items-table").on('click', '.modificar_item', select_item);
  $("#save_compra").on('click', save);
  $("[name=moncodi_]").on('change', set_inputhidden_value);
  $(".pagos-button").on('click', ver_pagos);
  $(".aceptar_guia").on('click', aceptar_guia);
  $("#newCliente").on('click', open_modal_cliente);
  $("[name=concodi]").on("change", agregar_dias);
})

H.init(set_inputhidden_value, initialFocus, setProductoInputSearchDefaultFocus);