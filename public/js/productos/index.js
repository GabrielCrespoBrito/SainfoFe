var tipo_accion = "create";
var tr_actual = null;
var form_accion = "#form-accion";
var id_elemento = null;
var cambiar_numero_operacion = true;
var agregar_familia = false;
var procodi;

exeWhenAjaxFinish = false;

// header 
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function funcWhenAjaxFinish() {

  console.log("set time out exe funcWhenAjaxFinish procodi window ", window.procodi);
  $("[name=numero_operacion]", form_accion).val(window.procodi);
  exeWhenAjaxFinish = false;
}

function tipo_accion_modal(accion = false) {
  if (accion) {
    tipo_accion = accion;
  }
  return tipo_accion;
}

function poner_error(input) {
  input.parents('.form-group').addClass('has-error');
}

function defaultErrorAjaxFunc(data) {
  let errors = data.responseJSON.errors;
  let mensaje = data.responseJSON.message;
  let erros_arr = [];
  console.log("errors", errors);
  for (prop in errors) {

    for (let i = 0; i < errors[prop].length; i++) {
      erros_arr.push(errors[prop][i]);
      let form = $(form_accion);
      poner_error($(form_accion + " input").filter("[name=" + prop + "]"));
    }

  }
  // console.log("error del ajax" , data );
  notificaciones(erros_arr, 'error', mensaje);
}

function ajaxs(data, url, funcs = {}) {

  let settings = {
    type: 'post',
    url: url,
    data: data,
    processData: false,
    contentType: false,
    success: function (data) {
      funcs.success ? funcs.success(data) : defaultSuccessAjaxFunc(data);
      // exeWhenAjaxFinish ? funcWhenAjaxFinish(data) : '';
    },
    error: function (data) {
      funcs.error ? funcs.error(data) : defaultErrorAjaxFunc(data);
    },
    complete: function (data) {
      funcs.complete ? funcs.complete(data) : null;
    }
  }
  $.ajax(settings);
};


function agregarASelect(data, select, name_codi, name_text) {

  let select_agregar = $("[name=" + select + "]");
  select_agregar.empty();

  for (let i = 0; i < data.length; i++) {
    let actual_data = data[i];

    let option = $("<option></option>")
      .attr('value', actual_data[name_codi])
      .text(actual_data[name_text]);

    select_agregar.append(option);
  }
}



function quitar_errores(inputs = false, form = form_accion) {
  if (inputs) {
    for (var i = 0; i < inputs.length; i++) {
      $(form).find("[name=" + inputs[i] + "]").parents(".form-group").removeClass('has-error');
    }
  }
  else {
    $(form).find(".form-group").removeClass('has-error');
  }
}



function modal_eliminacion_cliente() {
  tr_actual = $(this).parents("tr");
  show_modal_eliminar_cliente()
}

function eliminar_cliente(data) {
  show_modal_eliminar_cliente("hide");
  tr_actual.css('outline', '2px solid red');
  tr_actual.hide(1000);
  table.draw();
}

function eliminacion_cliente() {

  let codigo = tr_actual.find("td:eq(0)").text();
  let tipo = tr_actual.find("td:eq(1)").text();

  let funcs = {
    success: eliminar_cliente
  };

  let data = {
    'codigo': codigo,
    'tipo': tipo,
  };

  ajaxs(data, url_eliminar_cliente, funcs);
}

function set_or_restar_select(select, value = false) {

  let select_element = $("#form-accion [name=" + select + "]");

  if (value !== false) {
    let option = select_element.find("option[value=" + value + "]");
    option.prop("selected", "selected");
  }

  else {
    let option = select_element.find("option").prop('disabled', false);
  }
}


function poner_data_form(input_name, value) {

  let formElement = $("#form-accion").find("[name=" + input_name + "]");

  if (formElement[0].nodeName == "INPUT") {
    formElement.val(value);
  }

  else {
    set_or_restar_select(input_name, value);
  }
}

function validar_input_number(input) {
  let val = input.val();
  let resp = false;

  if (val.length) {
    if (!isNaN(Number(val))) {
      resp = true;
    }
  }

  return resp;
}

function calculos() {

  quitar_errores(["costo", "utilidad", "precio_venta"]);

  let costo = $(form_accion).find('[name=costo]');
  let utilidad = $(form_accion).find('[name=utilidad]');
  let precio_venta = $(form_accion).find('[name=precio_venta]');

  const costo_val = costo.val();
  const utilidad_val = utilidad.val();
  const precio_venta_val = precio_venta.val();

  console.log("caclulando");

  let valid = true;

  if( !validar_input_number(costo)  ){
    poner_error(costo);
    valid = false;
  }

  if (!validar_input_number(utilidad)) {
    poner_error(utilidad);
    valid = false;
  }

  if (!validar_input_number(precio_venta)) {
    poner_error(precio_venta);
    valid = false;
  }

  if(!valid){
    console.log("valores ok", costo_val, utilidad_val, precio_venta_val);
    return;
  }

  var calcular_precio_venta = function (costo, utilidad) {
    let costo_1porc = Number(costo_val) / 100;
    return Number((costo_1porc * Number(utilidad_val)).toFixed(2));
  };

  var calcular_utilidad = function (precio_venta, costo) {
    return Number((((Number(precio_venta_val) / Number(costo_val)) - 1) * 100).toFixed(2));
  };


  // calcular precio de venta tomando en cuenta el costo y la utilidad
  if ($(this).is(costo) || $(this).is(utilidad)) {
    let nuevo_precio_venta = calcular_precio_venta(costo, utilidad);
    precio_venta.val((Number(costo.val()) + nuevo_precio_venta));
  }

  // precio de venta
  else {
    let nueva_utilidad = calcular_utilidad(precio_venta, costo);
    utilidad.val(nueva_utilidad);
  }

} // end calculo

function modal_modificar() {
  tipo_accion_modal("edit");
}

function show_modal_eliminar_cliente(action = "show") {
  $("#modalEliminarCliente").modal(action);
}


function show_modal(action = "show", modal = "#modalAccion", backdrop = true) {
  $(modal).modal(action);
  return;

  backdrop = backdrop || "static";
  action = action == "show";
  $(modal).modal({
    show: action,
    backdrop: backdrop,
  });
}

function poner_value_codigo(value) {
  $(form_accion).find("[name=codigo]").val($.trim(value));
}

function poner_value_noperacion(value) {
  // console.log( "input_numero_operacion: " , $.trim(value) );
  $(form_accion).find("[name=numero_operacion]").val($.trim(value));
}

function activateDesactivatePrices() {
  // let activeInputs = tipo_accion == "create";
  let activeInputs = true;
  //  tipo_accion == "create";

  console.log("activateDesactivatePrices", activeInputs);

  $("[name=costo],[name=utilidad],[name=precio_venta],[name=peso]").prop('disabled', !activeInputs);

  if (activeInputs) {
    $(".div-unidad-link").hide();
  }

  else {
    $(".div-unidad-link").show();
    let href = $(".unidad-link").attr('data-href');
    href = href.replace('XX', id_elemento);
    $(".unidad-link").attr('href', href);
  }

  // let href = $(".unidad-link").attr('href');
}

// seleccionar una opción por defecto al select
function select_value(select_name, value) {
  let select = $("[name=" + select_name + "]", form_accion);
  select.find('opcion').prop('selected', false);
  select.find("option[value=" + value + "]").prop('selected', true)
}

function clearSelect2() {
  // $("#cod_sunat").select2('remove');
  $("#cod_sunat").attr({
    'data-id': '',
    'data-text': ''
  })
    .select2('destroy')
    .empty();

}

function set_data_modal(data) {
  console.log("activateDesactive", data);
  // activateDesactivatePrices();

  window.procodi = data.ProCodi;
  exeWhenAjaxFinish = true;

  console.log("form_accion", data);
  limpiar_modal(form_accion, [setDefaultValues, clearSelect2]);
  quitar_errores();
  agregar_familia = data.famcodi;
  select_value('grupo', data.grucodi);
  consultar_grupo();
  select_value('marca', data.marcodi);
  select_value('procedencia', data.marcodi);
  select_value('tipo_existencia', data.tiecodi);
  select_value('moneda', data.moncodi);
  select_value('unidad', data.unpcodi);
  select_value('base_igv', data.BaseIGV);
  $("[name=codigo]", form_accion).val(data.ID);
  // $( "[name=numero_operacion]" , form_accion ).val( data.ProCodi );
  $("[name=codigo_barra]", form_accion).val(data.ProCodi1);
  $("[name=nombre]", form_accion).val(data.ProNomb);
  $("[name=igv_porc]", form_accion).val(data.proigvv);
  let costo = (data.moncodi == "01") ? data.ProPUCS : data.ProPUCD;
  let precio_venta = (data.moncodi == "01") ? data.ProPUVS : data.ProPUVD;
  let precio_min_venta = (data.moncodi == "01") ? data.ProPMVS : data.ProPMVD;
  $("[name=costo]", form_accion).val(costo);
  $("[name=utilidad]", form_accion).val(data.ProMarg);
  $("[name=precio_venta]", form_accion).val(precio_venta);
  $("[name=precio_min_venta]", form_accion).val(precio_min_venta);
  $("[name=peso]", form_accion).val(data.ProPeso);
  $("[name=isc]", form_accion).val(data.ISC);
  $("[name=porc_com_vend]", form_accion).val(data.porc_com_vend ? data.porc_com_vend : 0);

  $("[name=ubicacion]", form_accion).val(data.proubic);
  
  const handleStock = Boolean(Number(data.ProSTem));

  $("#ProSTem").prop('checked', handleStock );

  const $stockMini = $("[name=stock_minimo]", form_accion)
  .val(data.Promini);


  handleStock ? $stockMini.removeAttr('readonly') : $stockMini.attr('readonly', 'readonly')


  $("[name=cuenta_venta]", form_accion).val(data.ctavta);
  $("[name=cuenta_venta]", form_accion).val(data.ctacpra);
  $("[name=descripcion]", form_accion).val(data.Proobse);
  $("[name=modo_uso]", form_accion).val(data.prouso);
  $("[name=ingredientes]", form_accion).val(data.proingre);
  $("[name=ultimo_costo]", form_accion).val(data.prosto1);
  $("[name=cto_prom]", form_accion).val(data.proproms);

  let total =
    Number(data.prosto1) +
    Number(data.prosto2) +
    Number(data.prosto3) +
    Number(data.prosto4) +
    Number(data.prosto5) +
    Number(data.prosto6) +
    Number(data.prosto7) +
    Number(data.prosto8) +
    Number(data.prosto9) +
    Number(data.prosto10);


  $("[name=almacen_n1]", form_accion).val(data.prosto1);
  $("[name=almacen_n2]", form_accion).val(data.prosto2);
  $("[name=almacen_n3]", form_accion).val(data.prosto3);
  $("[name=almacen_n4]", form_accion).val(data.prosto4);
  $("[name=almacen_n5]", form_accion).val(data.prosto5);
  $("[name=almacen_n6]", form_accion).val(data.prosto6);
  $("[name=almacen_n7]", form_accion).val(data.prosto7);
  $("[name=almacen_n8]", form_accion).val(data.prosto8);
  $("[name=almacen_n9]", form_accion).val(data.prosto9);
  $("[name=almacen_n10]", form_accion).val(data.prosto10);
  $("[name=almacen_total]", form_accion).val(total);



  if (data.profoto2) {
    $("[name=profoto2]", form_accion).attr({
      'data-id': data.cod_sunat.id,
      'data-text': data.cod_sunat.id + ' - ' + data.cod_sunat.descripcion,
    });
  }

  applySelect2();


  let hasImpuestoBolsa = data.icbper == "1";
  let hasPercepcion = data.ProPerc == "1";
  let hasIncluyeIGV = data.incluye_igv == "1";

  let estado = data.proesta == "1";

  $("[name=icbper]", form_accion).prop('checked', hasImpuestoBolsa);
  $("[name=ProPerc]", form_accion).prop('checked', hasPercepcion);
  $("[name=incluye_igv]", form_accion).prop('checked', hasIncluyeIGV);
  $("[name=estado]", form_accion).prop('checked', estado);


  console.log("setDataModal", data);

  setTimeout(funcWhenAjaxFinish, 1000);
}


function consultas_ajax(consulta) {

  let data = new FormData();

  data.append('codigo', id_elemento);
  data.append('grupo', $("#form-accion [name=grupo]").val());
  data.append('familia', $("#form-accion [name=familia]").val());
  data.append('marca', $("#form-accion [name=marca]").val());

  let info_consulta = {

    // codigo
    codigo: {
      url: url_consultar_codigo,
      funcs: { success: poner_value_codigo },
      data: {}
    },

    // noperación
    noperacion: {
      url: url_consultar_noperacion,
      funcs: { success: poner_value_noperacion },
      data: data
    },

    // datos
    datos: {
      url: url_consultar_datos,
      funcs: { success: set_data_modal },
      data: data
    },
  };

  let info = info_consulta[consulta];
  ajaxs(info.data, info.url, info.funcs);
}


function activeDisabledTabMovimiento(active = true) {
  let $liTab = $(".tabs-edit").parents('li');
  active ? $liTab.removeClass('disabled') : $liTab.addClass('disabled');
}

function modal_create() {
  agregar_familia = false;
  showHideTabs();
  activeDisabledTabMovimiento(false);
  quitar_errores();
  limpiar_modal(form_accion, [setDefaultValues, clearSelect2]);
  applySelect2();
  tipo_accion_modal("create");
  consultas_ajax("codigo");
  consultas_ajax("noperacion");
  activateDesactivatePrices();
  $("[name=incluye_igv]").prop('checked', true);
  $(".tab-info").tab('show');
  $("#ProSTem").prop('checked', true);
  activarDesactivarManejoStock();
  show_modal();
}




function setIDFormKardex(articulo_id) {
  console.log({ articulo_id })
  $("[name=articulo_desde]", '.formReporteKardex').val(articulo_id)
  $("[name=articulo_hasta]", '.formReporteKardex').val(articulo_id)
}


function modal_edit() {
  let $tr = $(this).parents('tr');
  id_elemento = $tr.find('td:eq(0)').text();
  showHideTabs();
  activeDisabledTabMovimiento(true);
  setIDFormKardex($tr.data().ID);
  tipo_accion_modal("edit");
  consultas_ajax("datos");
  activateDesactivatePrices();

  if ($(this).is('.movimiento_elemento')) {
    $(".tab-movimiento").tab('show');
  }
  else {
    $(".tab-info").tab('show');
  }

  show_modal();
}

function buscar_table(data) {
  table.search(data.ProCodi).draw()
}

function success_create(data) {
  notificaciones("Producto creado exitosamente", "success");
  show_modal("hide");
  limpiar_modal()
  quitar_errores();
  $(`.select-field-producto option[value=codigo]`).prop('selected', true);
  table.search(data.ProCodi).draw()
}

function success_edit(data) {
  notificaciones("Producto modificado exitosamente", "success");
  show_modal("hide");
  table.draw()//.search( data.ProCodi )
}



function crear_edit() {
  var formData = new FormData($(form_accion)[0]);
  var imagen = $("[name=imagen]")[0].files[0];
  var imagen_campo = imagen = "undefined" ? '' : imagen;
  formData.append('imagen', imagen_campo);

  let data = formData;
  // loremp-ipsum-odlor-
  console.log( "data" , data );

  let accion = tipo_accion_modal();
  // ProSTem
  let urls = {
    "create": url_crear,
    "edit": url_edit
  };

  let funcs = {
    "create": {
      "success": success_create,
    },
    "edit": {
      "success": success_edit,
    }
  };
  //   

  let funcs_accion = funcs[accion];
  let url = urls[accion];

  ajaxs(data, url, funcs_accion);
}

function agregar_select_familias(familias) {

  if (familias.length) {
    agregarASelect(familias, "familia", "famCodi", "famNomb");
    $("[name=grupo] option:selected").attr('data-familias', JSON.stringify(familias));
    // Test A/B
    if (agregar_familia) {
      select_value('familia', agregar_familia);
    }
    cambiar_noperacion()
  }

  else {
    agregarASelect([{ famCodi: null, famNomb: '-- Sin familias  --' }], "familia", "famCodi", "famNomb");
  }
}

function consultar_grupo() {

  let grupo = $("[name=grupo] option:selected", form_accion);
  let id_grupo = grupo.val();
  let familias = grupo.data('familias');
  console.log("familias del grupo", grupo.text(), familias);

  if (familias.length) {
    agregarASelect(familias, "familia", "famCodi", "famNomb");
    cambiar_noperacion();
  }
  else {
    var formData = new FormData();
    formData.append('id_grupo', id_grupo);
    let funcs = {
      success: agregar_select_familias,
    };
    ajaxs(formData, url_buscar_familias, funcs);
  }
}

function consultar_grupo_filter() {
  let grupo = $("[name=grupo_filter] option:selected");
  let id_grupo = grupo.val();
  let familias = grupo.data('familias');
  let familia_empty = { famCodi: null, famNomb: '-- SELECCIONAR FAMILIA --' };
  if (!id_grupo) {
    agregarASelect([familia_empty], "familia_filter", "famCodi", "famNomb");
    table.draw();
  }

  else {
    if (familias.length) {
      agregarASelect(familias, "familia_filter", "famCodi", "famNomb");
      table.draw();
    }
    else {
      var formData = new FormData();
      formData.append('id_grupo', id_grupo);
      let funcs = {
        success: function (familias) {
          if (familias.length) {
            agregarASelect(familias, "familia_filter", "famCodi", "famNomb");
            $("[name=grupo_filter] option:selected").attr('data-familias', JSON.stringify(familias));
            if (agregar_familia) {
              select_value('familia_filter', agregar_familia);
            }
          }
          else {
            agregarASelect([familia_empty], "familia_filter", "famCodi", "famNomb");
          }
          table.draw();
        },
      };
      ajaxs(formData, url_buscar_familias, funcs);
    }
  }
}

function setDefaultValues() {
  $("[data-default]", form_accion).each(function () {
    let default_value = $(this).attr('data-default');
    $(this).val(default_value);
  });
}

function cambiar_noperacion() {

  if (tipo_accion == "create") {
    consultas_ajax('noperacion');
  }
}


function limpiar_modal(form = form_accion, another_funcs = null) {
  $("[name=ProPerc]", form).prop('checked', false);
  $("[name=icbper]", form).prop('checked', false);
  $("[name=estado]", form_accion).prop('checked', true);

  $(form + " input").val("");
  if (another_funcs) {
    for (var i = 0; i < another_funcs.length; i++) {
      another_funcs[i]();
    }
  }
}


function eliminar_producto(e) {
  e.preventDefault();
  let tr = $(this).parents("tr");
  if (confirm("Esta seguro que desea eliminar?")) {
    let formData = new FormData();
    formData.append('id', tr.data().ID );
    ajaxs(
      formData,
      url_eliminar,
      {
        success: function (data) {
          notificaciones(data.message, "success");
          tr.hide(500, function () {
            $(this).remove();
          })
          table.draw();
        },
        error: function (data) {
          console.log("erro", data.responseJSON.message);
          notificaciones(data.responseJSON.message, "error")
        }
      });
  }
}


function restaurar_producto(e) {
  e.preventDefault();
  let tr = $(this).parents("tr");
  if (confirm("Esta seguro que desea restaurar?")) {
    let formData = new FormData();
    // formData.append('id', tr.data().ID  tr.find(':eq(0)').text());
    formData.append('id', tr.data().ID  );
    ajaxs(
      formData,
      url_restaurar,
      {
        success: function (data) {
          notificaciones(data.message, 'success', 'Accion exitosa')
          poner_value_codigo(data.codigo);
          table.draw();
        },
        complete: () => {
          $(".select-field-producto").find('option[value=codigo]').prop('selected', true);
          table.draw();
        }
      });
  }
}


function poner_stocks(stocks) {
  $("[name=almacen_n1]", form_accion).val(stocks.prosto1);
  $("[name=almacen_n2]", form_accion).val(stocks.prosto2);
  $("[name=almacen_n3]", form_accion).val(stocks.prosto3);
  $("[name=almacen_n4]", form_accion).val(stocks.prosto4);
  $("[name=almacen_n5]", form_accion).val(stocks.prosto5);
  $("[name=almacen_n6]", form_accion).val(stocks.prosto6);
  $("[name=almacen_n7]", form_accion).val(stocks.prosto7);
  $("[name=almacen_n8]", form_accion).val(stocks.prosto8);
  $("[name=almacen_n9]", form_accion).val(stocks.prosto9);
  $("[name=almacen_n10]", form_accion).val(stocks.prosto10);
  $("[name=almacen_total]", form_accion).val(stocks.total);
}

function updateStock(e) {
  e.preventDefault();

  $("#load_screen").show();

  let funcs = {
    success: (data) => {
      notificaciones(data.message, 'success', 'Accion exitosa')
      poner_stocks(data.stocks);

    },
    complete: () => {
      $("#load_screen").hide();
    }
  };

  let url = $(this).attr('data-action');
  let formData = new FormData();
  formData.append('id', id_elemento);
  formData.append('local', $("[name=local_stock]").val());

  ajaxs(formData, url, funcs);
}


function setCodeBarra(codigo, cantidad) {
  codigo = codigo.toLowerCase();

  let $modal = $("#modalAccion");

  if ($modal.is('.fade.in')) {
    let accion = tipo_accion_modal();
    let $input_codigo_barra = $("[name=codigo_barra]");
    if (accion == "create") {
      $input_codigo_barra.val(codigo);
    }
    else {
      let codigo_barra = $.trim($input_codigo_barra.val());
      if (codigo_barra) {
        if (confirm(`Desea cambiar el codigo de barra de (${codigo_barra}) al el nuevo codigo scaneado (${codigo})`)) {
          $input_codigo_barra.val(codigo);
        }
      }
      else {
        if (confirm(`Desea poner el codigo de barra de (${codigo})`)) {
          $input_codigo_barra.val(codigo);
        }
      }
    }
  }
  else {
    table.search('')
    table.search(codigo.toUpperCase())
    $(".select-field-producto").find('option[value=codigo_barra]').prop('selected', true)
    table.draw();
  }
}


function handleManejo() {
  activarDesactivarManejoStock();
}


function activarDesactivarManejoStock() {

  $("#ProSTem").attr('value', 1);

  const isChecked = $("#ProSTem").prop('checked');
  const $stockMini = $("[name=stock_minimo]");
  isChecked ? $stockMini.removeAttr('readonly') : $stockMini.attr('readonly', 'readonly')
}


function events() {

  // Initialize with options
  onScan.attachTo(document, {
    suffixKeyCodes: [13], // enter-key expected at the end of a scan
    reactToPaste: false, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
    onScan: setCodeBarra,
    onKeyDetect: function (iKeyCode) { // output all potentially relevant key events - great for debugging!
      // console.log('Pressed: ' + iKeyCode);
    }
  });

  $("table").on('click', '.eliminar_elemento', eliminar_producto);
  $("table").on('click', '.restaurar_elemento', restaurar_producto);

  // Actualizar stock
  $(".update-stock").on('click', updateStock);
  $(".crear-nuevo").on('click', modal_create);
  $("#datatable").on('click', '.modificar_elemento', modal_edit);

  // cambiar noperación
  $("[name=familia],[name=marca]", "#form-accion").on('change', cambiar_noperacion);

  $("[name=costo],[name=utilidad],[name=precio_venta]", "#form-accion").on('keyup', calculos);

  // consultar y agregar familia correspondiente a un grupo
  $("[name=grupo]", "#form-accion").on('change', consultar_grupo);
  $("#ProSTem", "#form-accion").on('change', handleManejo);
  $("[name=grupo_filter]").on('change', consultar_grupo_filter);

  $("[name=familia_filter], [name=deleted]").on('change', () => {
    table.draw()
  });

  // guardar informacion 
  $(".send_info").on('click', crear_edit);

  $(".send_info_kardex").on('click', sendFormKardex);

  $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
    if ($(e.target).parents('li').is('.disabled')) {
      e.preventDefault();
      return false;
    }
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', showHideTabs);
}

function showHideTabs() {
  let $target = $("#form-accion .nav-tab-main").find('li.active').find('a');
  let showBtnProductoFormSave = $target.is('.tab-movimiento');

  let $btnProductFormSave = $(".div-btn-form-save");
  let $btnProductoFormKardex = $(".div-btn-form-kardex");

  !showBtnProductoFormSave ? $btnProductFormSave.show() : $btnProductFormSave.hide();
  showBtnProductoFormSave ? $btnProductoFormKardex.show() : $btnProductoFormKardex.hide();

  console.log("showHideTabs", showBtnProductoFormSave);
}

function sendFormKardex() {
  // ------------------------------------------------------------
  let $formReporteKardex = $(".formReporteKardex");
  let $formReporte = $(".formReporte");
  let fecha_desde = $formReporte.find("[name=fecha_desde]").val();
  let fecha_hasta = $formReporte.find("[name=fecha_hasta]").val();
  let almacen = $formReporte.find("[name=LocCodi]").val();  
  $formReporteKardex.find('[name=fecha_desde]').val(fecha_desde)
  $formReporteKardex.find('[name=fecha_hasta]').val(fecha_hasta)
  $formReporteKardex.find('[name=LocCodi]').val(almacen)
  $formReporteKardex.submit();
}

function applySelect2() {
  initSelect2("#cod_sunat");
}


init(
  events,
  applySelect2,
  limpiar_modal,
  datepicker,
  ajaxs_setting.bind(null, { processData: false, contentType: false })
);