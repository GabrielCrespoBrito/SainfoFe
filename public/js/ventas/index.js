
var popover_current = null;
var tag_input;
var id_factura = null;
var tr_selected;

function successEmailSend(data) {
  show_hide_modal("modalRedactarCorreo", false);
  estadoCorreo(false);
  $(".corre_asunto").val("");
  $(".corre_mensaje").val("");
  notificaciones("Correo enviado exitosamente", "success");
}

function estadoCorreo(mandando = true) {

  let b = $(".send_correo");
  if (mandando) {
    b.removeClass('btn-success')
      .addClass('btn-default disabled')
      .text("Enviado correo");

    b.find("span")
      .removeAttr('class')
      .attr('class', 'fa fa-spin fa-spinner');
  }

  else {
    b
      .removeClass('btn-default disabled')
      .addClass('btn-success')
      .text("Enviar");


    b.find("span")
      .removeAttr('class')
      .attr('class', 'fa fa-envelope');
  }
}

//processData: false 

function enviar_correo() {
  let hasta = $(".corre_hasta").val();
  let asunto = $(".corre_asunto").val();
  let mensaje = $(".corre_mensaje").val();
  let documentos = $(".corre_documentos").val();
  estadoCorreo(true);


  if (hasta.length > 145) {
    notificaciones("La cantidad de correos excede lo permitido", "error");
  }

  if (!hasta.length) {
    notificaciones("Es necesaria llenar colocar el destinatario", "error");
    $("[name=corre_hasta]").focus();
    return;
  }

  var searchIDs = $(".corre_documentos:checked").map(function () {
    return $(this).val();
  }).get();


  let formData = new FormData();
  formData.append('id_factura', tr_selected.find('td:eq(0)').text().trim());
  formData.append('hasta', hasta);
  formData.append('asunto', asunto);
  formData.append('mensaje', mensaje);
  formData.append('documentos', searchIDs);

  let funcs = {
    success: successEmailSend,
    error: function (data) {
      estadoCorreo(false);
      if (data.responseJSON.errors) {
        defaultErrorAjaxFunc(data)
      }
      else {
        let message = "No se pudo enviar el correo por el, por favor revise sus datos de correo electronico (" + data.responseJSON.message + ")";
        notificaciones(message, "error");
      }
    }
  }

  ajaxs(formData, url_send_email, funcs)
}

function fixedNumber(data, type, row, meta) {
  return Number(data).toFixed(2);
}

function initTags() {
  var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  tag_input = $("#tags").tagsInput({
    'unique': true,
    'minChars': 10,
    'maxChars': 60,
    'validationPattern': new RegExp(re)
  });
}

function initDatable() {
  table = $('#datatable').DataTable({
    "pageLength": 10,
    "responsive": true,
    "processing": false,
    'sorting': false,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra documento"
    },
    "initComplete": function initComplete(settings, json) {
      $('div.dataTables_filter input').attr('placeholder', 'Buscar Documento');
    },
    "serverSide": true,
    // "order": [[ 0, "desc" ]],
    "ajax": {
      "url": url_venta_consulta,
      "data": function (d) {
        return $.extend({}, d, {
          "buscar_por_fecha": $("[name=buscar_por_fecha]").val(),
          "fecha_desde": $("[name=fecha_desde]").val(),
          "fecha_hasta": $("[name=fecha_hasta]").val(),
          "tipo": $("[name=tipo] option:selected").val(),
          "local": $("[name=local] option:selected").val(),
          "estadoAlmacen": $("[name=estadoAlmacen] option:selected").val(),
          "status": $("[name=status]").val(),
        });
      }
    },
    "columns": [
      { data: 'nro_venta', sorting: false, orderable: false, searchable: false },
      { data: 'TidCodi', orderable: false, searchable: false },
      {
        data: 'VtaNume', orderable: false, searchable: false, render: function (data, type, row, meta) {
          return row.VtaSeri + "-" + row.VtaNumee;
        }
      },
      { data: 'VtaFvta', orderable: false, searchable: false },
      { data: 'PCNomb',  orderable: false, searchable: false, render: function (data) { return data.slice(0, 15).concat("...") } },
      { data: 'monabre', orderable: false, searchable: false },
      { data: 'VtaImpo', orderable: false, searchable: false, render: fixedNumber, className: 'text-right-i' },
      { data: 'VtaPago', orderable: false, searchable: false, render: fixedNumber, className: 'text-right-i' },
      { data: 'VtaSald', orderable: false, searchable: false, render: fixedNumber, className: 'text-right-i' },
      { data: 'alm',     orderable: false, searchable: false },
      { data: 'estado',  orderable: false, searchable: false, className: 'estado' },
      { data: 'btn',     orderable: false, searchable: false }
    ]
  });


  window.table_ventas = table;

}

function initPicker(format = "yyyy-mm-dd") {
  $('[name=fecha_desde],[name=fecha_hasta]').datepicker({
    autoclose: true,
    format: format,
  });
}

function eliminar_ele(e) {
  e.preventDefault()
  e.stopPropagation();

  if (confirm("Desea quitar el Documento?")) {

    let formData = new FormData();
    formData.append('id_factura', id_factura);
    let funcs = { success: borradoExitoso };
    ajaxs(formData, url_eliminar_factura, funcs);
  }
}

function checkChange(column, data) {
  if (Number(data)) {
    return "si";
  }
  else {
    return "no";
  }
}

function successMails(data) {
  let mail_cliente = data.documento.cliente_with ? data.documento.cliente_with.PCMail : '';
  let columns = [
    { 'name': 'DetItem' },
    { 'name': 'user.usulogi' },
    { 'name': 'DetFecha' },
    { 'name': 'DetEmail' },
    { 'name': 'DetPDF', render: checkChange },
    { 'name': 'DetPDF', render: checkChange },
    { 'name': 'DetCDR', render: checkChange },
    { 'name': 'DetAsun' },
    { 'name': 'DetMens' },
  ];

  show_hide_element(".checkbox_pdf", data.documento.VtaPDF)
  show_hide_element(".checkbox_xml", data.documento.VtaXML)
  show_hide_element(".checkbox_cdr", data.documento.VtaCDR)

  let has_documentos =
    data.documento.VtaPDF ||
    data.documento.VtaCDR ||
    data.documento.VtaXML;

  cl(
    "has_documentos pdf, cdr , xml ",
    has_documentos,
    data.documento.VtaPDF,
    data.documento.VtaCDR,
    data.documento.VtaXML
  );

  show_hide_element(".has-documentos", !has_documentos)

  $('#tags').removeTag();
  $('#tags').val(mail_cliente);
  add_to_table("#emails-enviados", data.mails, columns);
  $("#modalRedactarCorreo").modal();

  return;
}


function ProbarImpresion(response) {
  printer = new printTicket(
    response.imprecion_data.data_impresion,
    response.imprecion_data.nombre_impresora
  );
  printer.print();
}

function showModalRedactarCorreo(e = false) {
  e.preventDefault();

  show_hide_modal("modalData", false);


  $.ajax({
    type: 'post',
    url: url_mails_enviados,
    data: {
      'id_factura': tr_selected.find("td:eq(0)").text().trim(),
    },
    success: successMails,
  });

  return false;
}

function buscar_table() {
  $("[name=buscar_por_fecha]").val(1);
  table.draw();
}

function borradoExitoso(data) {
  notificaciones("Factura eliminado exitosamente", "success")
  table.draw();
}


function anulado_exitoso(data) {
  table.draw();
}

function anular_documento() {
  let select = $(".seleccionado");

  if (select.find("td:eq(1)").text() == "03") {
    if (select.find("td:eq(11)").text() != "A") {
      if (confirm("Seguro que desea anular esta boleta?")) {
        ajaxs({ id_boleta: select.find("td:eq(0)").text() }, url_anular_boleta, { success: anulado_exitoso })
      }
    }

    else {
      notificaciones("Esta boleta ya fue anulada", "error");
    }
  }
  else {
    notificaciones("Solo se puede anular boletas", "error");
    // alert("Solo se puede anular boletas");
  }
}


function activatePopover() {
  var $btns = $("[data-toggle='popover-x']");
  if ($btns.length) {
    $btns.popoverButton({
      closeOpenPopovers: true,
      keyboard: false,
      backdrop: false,
      trigger: 'click'
    });
  }
}

function guardar_guia_salida() {
  if (confirm("Esta seguro que desea confirmar la guia de salida")) {

    let funcs = {
      success: function (data) {
        table.draw()
        show_hide_modal("modalAsignarGuia", false);
        notificaciones("Guia guardada exitosamente", 'success');
      },
    };


    let formData = new FormData();
    formData.append('id_almacen', $("[name=almacen_id]").val());
    formData.append('id_movimiento', $("[name=tipo_movimiento]").val());
    formData.append('is_electronica', Number($("[name=is_electronica]").is(':checked')));
    formData.append('id_factura', id_factura);

    ajaxs(formData, url_save_guiasalida, funcs)
  }
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

function getNombreTipoDocumento(td) {
  switch (td) {
    case "01":
      return 'Factura'
      break;
    case "03":
      return 'Boleta'
      break;
    case "07":
      return 'Nota de credito'
      break;
    case "08":
      return 'Nota de debito'
      break;
    case "52":
      return 'Nota de venta'
      break;
    default:
      return "";
      break;
  }
}


function show_info_documento(html) {
  let $modalData = $("#modalData");

  $("#modalData").find('.modal-dialog').attr('class', 'modal-dialog modal-sm');

  let tdNombre = getNombreTipoDocumento(tr_selected.find('td:eq(1)').text());
  let title = tdNombre + " " + tr_selected.find('td:eq(2)').text();

  $modalData.find('.modal-title').text(title);
  $modalData.find('.modal-body').html(html);
  $modalData.modal();
}

function get_info_documento() {
  let url = $(this).attr('data-url');
  let funcs = {
    success: show_info_documento,
    complete: function (data) {
    }
  }
  ajaxs({}, url, funcs);
}

function setTipoGuia() {
  let $selectTipo = $("[name=tipo_movimiento]");
  let data = is_nc ? $selectTipo.data('nc') : $selectTipo.data('regular');
  agregarASelect(data, 'tipo_movimiento', 'Tmocodi', 'TmoNomb')
}

function anular_ele(e) {
  e.preventDefault();
  if (confirm("Desea anular esta nota de venta")) {
    $("#load_screen").show();
    let url = $(this).attr('data-url');
    let funcs = {
      success: function (data) {
        notificaciones(data.message, 'success');
        show_hide_modal("modalData", false);
      },
      complete: function (data) {
        $("#load_screen").hide();
        window.table.draw();
      }
    }
    ajaxs({}, url, funcs);
  }
  return false;
}

function events() {
  appCG.init();

  appCG.registerSuccessActions({
    'reloadTable': function () { table.draw() }
  });

  $("body").on('change', '.formato_pdf', function () {
    let formato = $(this).val();
    let $enlace = $(".pdf-enlace");
    $enlace.each(function (index, dom) {
      let $this = $(dom);
      let oldHref = $this.data('href_default');
      let newHref = oldHref.replace('@@', formato);
      $this.attr('href', newHref);
    })
  });

  $("*").on('click', '.asignarguia', function (e) {

    e.preventDefault();

    id_factura = tr_selected.find('td:eq(0)').text().trim();
    appCG.showModal(id_factura);

    return false;
  });

  // $("*").on('click',"[data-toggle='popover-x']" , function(){
  $("*").on('click', ".btn-popover", function () {
    tr_selected = $(this).parents('tr');
  })

  $(".aceptar_guia").on('click', guardar_guia_salida);
  $(".buscar_factura_b").on('click', buscar_table);
  $("*").on('click', '.eliminar-accion', eliminar_ele);
  $("*").on('click', '.accion_anular', anular_ele);
  $(".send_correo").on('click', enviar_correo);
  $("table").on('click', ".btn-popover", get_info_documento);

  $("#modalData").on('click', ".download-all", get_info_documento);


  function ponerAccionEnlace() {
    let isChecked = this.checked;
    let $enlaceConsult = $("#modalData .accion_consult");
    let href = $enlaceConsult.attr('data-href');
    href = href.replace('@@', Number(isChecked));
    $enlaceConsult.attr('data-url', href);
  }



  $("*").on('click', ".accion_consult", function (e) {

    $("#load_screen").show();

    e.preventDefault();

    let data = {};
    let url = $(this).attr('data-url');

    let funcs = {
      success: (data) => {
        notificaciones(data.message, 'success');
      },
      complete: function () {
        $("#load_screen").hide();
        table.draw();
        $(".popover").hide();
      }
    }

    ajaxs(data, url, funcs);

    return false;
  });


  $(".anular-accion").on('click', anular_documento);
  $("[name=tipo],[name=local]").on('change', function () { table.draw() });
  $("*").on('click', '.accion_email', showModalRedactarCorreo);
  $("*").on('change', '[name=descargar_cdr]', ponerAccionEnlace);

  table.on('draw.dt', activatePopover);

  $("[name=estadoAlmacen]").on('change', function () {
    table.draw();
  }),


    $("#modalRedactarCorreo").on('show.bs.modal', function () {
      $(".popover").hide();
    });
}

function init() {
  initPicker();
  initDatable();
  initTags();
  events();
  window.ajax_default = { processData: false, contentType: false, };



  $("*").on('click', '.imprimir-directo', function (e) {

    e.preventDefault();
    let data = {};
    let url = $(this).attr('data-url');
    let funcs = {
      success: ProbarImpresion,
      complete: function () {
        $("#load_screen").hide();
      }
    }
    ajaxs(data, url, funcs);

    e.preventDefault()
    return false;
  });


  $("*").on('click', '[data-nc]', function (e) {

    e.preventDefault();

    const $modal = $("#modalNC");

    let documentoName = $("#modalData").find('.modal-title').text();

    $modal.find('.modal-title').text('Crear Nota de Credito:  ' + documentoName);

    $("#modalData").modal('hide');

    const url = $(this).data('url');
    const url_store = $(this).data('url_store');

    window.form_nota_credito.fetchInfo(url, url_store)

    $modal.modal();

    return false;
  });

  $("*").on('click', '[data-nd]', function (e) {

    e.preventDefault();

    const $modal = $("#modalND");
    
    $("#modalData").modal('hide');

    let documentoName = $("#modalData").find('.modal-title').text();
    $modal.find('.modal-title').text('Crear Nota de Debito:  ' + documentoName);

    const url = $(this).data('url');
    const url_store = $(this).data('url_store');

    window.form_nota_debito.fetchInfo(url, url_store)

    $modal.modal();

    return false;
  });


  $("#modalData").on('click', '.send-whatapp', function (e) {

    if (!validateNumberWhatApp()) {
      e.preventDefault();
      return false;
    }

    $(this).attr('href', getEnlaceWhatApp());
  });


  function validateNumberWhatApp() {
    const $modal = $("#modalData");
    const $divInputNumber = $modal.find(".div-number-whatapp");
    const $inputNumber = $divInputNumber.find('input');

    if (!$divInputNumber.is(':visible')) {
      $divInputNumber.show();
      $inputNumber.focus();
      return false;
    }

    const inputLength = $.trim($inputNumber.val()).length;

    if (inputLength == 0) {
      notificaciones("Escriba Un Nùmero de Telefono", "warning");
      $inputNumber.focus();
      return false;
    }

    return true;
  }

  function getEnlaceWhatApp() {
    const $modal = $("#modalData");

    const text = $modal.find('.send-whatapp').attr('data-text');
    const url = new URL("https://api.whatsapp.com/send");
    const phone = "+51" +  $modal.find('.div-number-whatapp input').val();
    url.searchParams.set('phone', phone)
    url.searchParams.set('text', text)
    return url.toString();
  }

  $("#modalData").on('keyup', '.div-number-whatapp input', function (e) {
    if (e.keyCode == 13) {
      if (validateNumberWhatApp()) {
        window.open(getEnlaceWhatApp(), '_blank');
      }
    }
  });

}

$(document).ready(init);



