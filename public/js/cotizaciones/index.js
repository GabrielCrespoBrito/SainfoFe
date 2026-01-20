

function active_or_disable_button(active = true, id = null) {
  let modificar_button = $(".modificar-accion");
  let eliminar_button = $(".eliminar-accion");
  let anular_button = $(".anular-accion");
  // let enviar_button = $(".enviar-correo");      
  let array_buttons = [modificar_button, eliminar_button, anular_button, enviar_button];

  for (let i = 0; i < array_buttons.length; i++) {
    if (active) {
      array_buttons[i].removeClass('disabled');
    }
    else {
      array_buttons[i].addClass('disabled');
    }
  }
}

function active_ordisable_trfactura(active = true, tr_factura) {
  if (active) {
    $(".seleccionado").removeClass('seleccionado');
    $(tr_factura).addClass('seleccionado');
  }
  else {
    $(".seleccionado").removeClass('seleccionado');
  }
}


function seleccionar_factura() {
  let tr = $(this);

  if (tr.find('.dataTables_empty').length) {
    return;
  }

  if (tr.is('.seleccionado')) {
    active_ordisable_trfactura(false);
    active_or_disable_button(false)
  }

  else {
    $('.seleccionado').removeClass('seleccionado');
    let id_cotizacion = $(this).find("td:eq(0)").text();
    let url_ed = url_editar.replace("XXX", id_cotizacion);
    $(".modificar-accion").attr('href', url_ed);
    active_or_disable_button(true)
    active_ordisable_trfactura(true, tr);
  }
}


function showModalRedactarCorreo() {
  $("#modalData").modal('hide');
  // console.log( $ )
  // $("#modalMail").modal();
  // let data = $(this).parents('tr').data('info');
  let data = tr_selected.data('info');
  let mail_cliente = data.cliente_with.PCMail;

  // console.log( "data", data , mail_cliente);

  $('#tags', "#modalMail").removeTag();
  $('#tags', "#modalMail").val(mail_cliente);
  $("#modalMail").modal();
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

function defaultErrorAjaxFunc(data) {
  let errors = data.responseJSON.errors;
  let mensaje = data.responseJSON.message;
  let erros_arr = [];
  for (prop in errors) {
    for (let i = 0; i < errors[prop].length; i++) {
      erros_arr.push(errors[prop][i]);
    }
  }
  console.log("error", erros_arr, mensaje);
  notificaciones(erros_arr, 'error', mensaje);
}


function headerAjax() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
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
      funcs.error ? funcs.error(data) : defaultErrorAjaxFunc(data);
    },
    complete: function (data) {
      executing_ajax = false;
      funcs.complete ? funcs.complete(data) : null;
    }
  });
};

function fixedNumber(data, type, row, meta) {
  return Number(data).toFixed(2);
}



function initDatable() {
  table = $('#datatable').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, "desc"]],
    "ajax": {
      "url": url_consulta,
      //
      "data": function (d) {
        return $.extend({}, d, {
          "mes": $("[name=mes] option:selected").val(),
          "tipo": $("[name=tipo]").val(),
          "local": $("[name=local] option:selected").val(),
          "estado": $("[name=estado] option:selected").val(),
          "vendedor": $("[name=vendedor] option:selected").val(),
          "usucodi": $("[name=usucodi] option:selected").val(),
        });
      }
      //      
    },
    "createdRow": function (row, data, index) {

      $(row).data('info', data);
      // if (data[5].replace(/[\$,]/g, '') * 1 > 150000) {
      // $('td', row).eq(5).addClass('highlight');
      // }
      // console.log("createdRow" , arguments );
    },
    "columns": [
      { data: 'numero', orderable: false, searchable: false },
      { data: 'CotFVta', orderable: false, searchable: false },
      { data: 'cliente_with.PCNomb', orderable: false, searchable: false },
      { data: 'usuario.usulogi', orderable: false, searchable: false },
      { data: 'moneda.monabre', orderable: false, searchable: false },
      { data: 'cotbase', 'class': 'text-right', render: fixedNumber, orderable: false, searchable: false },
      { data: 'cotigvv', 'class': 'text-right', render: fixedNumber, orderable: false, searchable: false },
      { data: 'cotimpo', 'class': 'text-right', render: fixedNumber, orderable: false, searchable: false },
      { data: 'estado', orderable: false, searchable: false },
      { data: 'venta', orderable: false, searchable: false },
      { data: 'accion', 'class': 'overflow-visible', orderable: false, searchable: false },

    ]
  });
}



function eliminar_ele(e) {
  e.preventDefault();

  if (confirm("Desea quitar el Documento?")) {

    let data = {
      id: $(".seleccionado td:eq(0)").text()
    };
    let funcs = {
      success: borradoExitoso
    }
    ajaxs(data, url_eliminar, funcs);
  }
}



function borradoExitoso(data) {

  let tr = $(".seleccionado");
  notificaciones("Elemento eliminado exitosamente", "success")
  tr.css('outline', '2px solid red');
  tr.hide(1000, function () {
    tr.remove();
    table.draw();
  });
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


function show_hide_modal(id_modal, action = "show", _static = true) {
  $("#" + id_modal).modal(action);
}


function set_emails_data(data) {
  $('#tags').val(data.mail);
  $('#tags').removeTag();
  show_hide_modal("modalMail")
}

function redactar_correo() {
  let id_cotizacion = $(".seleccionado").find('td:eq(0)').text();
  $.ajax({
    type: 'post',
    url: url_mail,
    data: {
      'id_cotizacion': id_cotizacion,
    },
    success: set_emails_data,
  });

  return;
}

function successEmailSend(data) {
  $("#modalMail").modal("hide");
  $(".corre_asunto").val("");
  $(".corre_mensaje").val("");
  notificaciones("Correo enviado exitosamente", "success");
}


function enviar_correo(e = null) {
  console.log(arguments);
  if (e) {
    e.preventDefault();
    // return;
  }

  $(".send_correo")
    .addClass('disabled')
    .text('Enviando');

  let hasta = $(".corre_hasta").val();
  let asunto = $(".corre_asunto").val();
  let mensaje = $(".corre_mensaje").val();
  let id_cotizacion = tr_selected.data('info').CotNume;

  let data = {
    'hasta': hasta,
    'asunto': asunto,
    'mensaje': mensaje,
    'id_cotizacion': id_cotizacion
  }

  if (hasta.length > 145) {
    notificaciones("La cantidad de correos excede lo permitido", "error");
    $("[name=corre_hasta]").focus();
  }

  if (!hasta.length) {
    notificaciones("Es necesaria llenar colocar el destinatario", "error");
    $("[name=corre_hasta]").focus();
    return;
  }

  console.log("success"), data;

  let funcs = {
    success: successEmailSend,
    error: function (data) {
      $(".corre_asunto").val("");
      $(".corre_mensaje").val("");
      notificaciones("No se pudo enviar el correo, por favor revise los datos", "error");
      show_modal("hide", "#modalMail");
    }, complete: () => {
      $(".send_correo")
        .removeClass('disabled')
        .text('Enviar')
    }
  }

  ajaxs(data, url_send_email, funcs)
  return false;
}



function imprimirTipoImpresion(e) {
  let $this = $(this);

  let $link = $this.attr('data-url');
  let $parent = $this.parents('.row');

  // let enlace = confirm('Desea imprimir visualizando el igv?') ? $link.replace('@@', 1) : $link.replace('@@', 0)

  let url = $link
    .replace('@@', $parent.find('[name=tipo]').val())
    .replace('_FORMATO_', $parent.find('[name=formato]').val())

  // console.log({ url });

  window.location = url;
  e.preventDefault();
  return false;
}


window.eliminar = function (e) {


  $("#modalData").modal('hide');

  e.preventDefault();
  let modal = $("#modalEliminate");
  let id = $(this).attr("data-id");
  let url = $("#formEliminate").attr('action').replace('XX', id);

  $("#formEliminate").attr('action', url);
  modal.modal();
}

function events() {
  $("*").on('click', '.enviar-correo', showModalRedactarCorreo);

  // $("*").on('change', '.cambiar-pdf', (e) => {
  //   console.log("cambiar_pdf", e)

  //   const $this = $(e.target);
  //   const $parent = $this.parents('.row');
  //   const $btn_print = $parent.find('.imprimir');
  //   const urlBase = $btn_print.attr('data-url');

  //   let url = urlBase
  //     .replace('@@', $parent.find('[name=tipo]').val())
  //     .replace('_FORMATO_', $parent.find('[name=formato]').val())

  //   $btn_print.attr('href', url );

  //   return false;
  // });

  $(".send_correo").on('click', enviar_correo);

  // ------------------------------------------------------------------------------------------------------------
  $("*").on('click', '.send-whatapp', function (e) {

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
    const phone = "+51" + $modal.find('.div-number-whatapp input').val();
    url.searchParams.set('phone', phone)
    url.searchParams.set('text', text)
    return url.toString();
  }

  // loremp-ipsum-odlor-markoff

  $("*").on('keyup', '.div-number-whatapp input', function (e) {
    console.log("dropdown.open", e);

    if (e.keyCode == 13) {
      if (validateNumberWhatApp()) {
        window.open(getEnlaceWhatApp(), '_blank');
      }
    }

    e.preventDefault;
    return false;

  });
  // -------------------------------------------------

  $("*").on('click', '.eliminate-element', window.eliminar);

  $("*").on('click', '.anular-btn', function (event) {
    event.stopImmediatePropagation();
    event.preventDefault();

    if (confirm("Esta Seguro que Desea anular este documento?") == false) {
      return false;
    }

    $("#load_screen").show();

    ajaxs(
      {},
      $(this).attr('href'),
      {
        success: (data) => {
          notificaciones('Anulacion Exitosa', 'success')
          table.draw();
        },
        complete: () => {
          ($("#load_screen").hide());
          $("#modalData").modal('hide');
        }
      }
    );

    return false;
  });



  $("table").on('click', ".dropdown", function () {
    id_factura = $(this).parents('tr').find("td:eq(0)").text();
  });

  $("[name=tipo],[name=mes],[name=local],[name=vendedor],[name=usucodi],[name=estado]").on('change', function () {
    table.draw();
  });

  $("*").on('click', '.imprimir', imprimirTipoImpresion);

  $("*").on('click', '.data-info', (e) => {

    console.log(e);

    window.tr_selected = $(e.target).parents('tr');

    const $modal = $("#modalData");
    $modal.attr('data-backdrop', 'true');

    $modal.find('.modal-dialog').removeClass('modal-md').addClass('modal-md');

    let $ele = $(e.target);

    if ($ele.is('span')) {
      $ele = $ele.parents('button');
    }

    const title = $ele.attr('data-title');
    const id = $ele.attr('data-id');
    const url_edit = $ele.attr('data-edit');
    const url_anular = $ele.attr('data-anular');
    const url_imprimir = $ele.attr('data-imprimir');
    const url_whatapp = $ele.attr('data-whatapp');
    const cliente_tlf = tr_selected.data('info').cliente_with.PCTel1

    const body = `
    <div class="ventas-info-ele">
    <div class="seccion seccion-acciones">
      <div class="title-section"> Compartir </div>
      <div class="col-md-12">
      <a href="#" data-id="${id}" class="btn btn-xs btn-default btn-block enviar-correo"> <span class="fa fa-envelope"></span> Enviar Email </a>
      </div>  
      <div class="col-md-12">
        <a href="#" target="_blank" data-text="${url_whatapp}" class="btn btn-xs btn-default btn-block send-whatapp"> <span style="color: #45c554;" class="fa fa-whatsapp"></span> Enviar WhatApp </a>
        </div>
        <div class="col-md-12 pb-x10 div-number-whatapp" style="text-align:center;display:none">
        <input placeholder="Nùmero de Telefono" type="number" name="numberWhatApp" class="input-sm input-sm form-control text-center" value="${cliente_tlf}">
        </div>
    </div>

    <div class="seccion seccion-acciones">
      <div class="title-section"> Acciones </div>
      <div class="row">
      <div class="col-md-8 no-pr">
        <a target='_blank' data-url="${url_imprimir}" href="${url_imprimir}" class="imprimir btn btn-xs btn-default btn-block"> Imprimir </a>
      </div>

      <div class="col-md-2 no_pl no_pr">
        <select class="cambiar-pdf" style="width:100%; height: 23px;" name="formato" style="width:100%;height:1.6em">
          <option value="a4">A4</option>
          <option value="ticket">Ticket</option>
        </select>        
      </div>

      <div class="col-md-2 no_pl">
        <select class="cambiar-pdf" style="width:100%; height: 23px;" name="tipo" style="width:100%;height:1.6em">
          <option value="igv"> Con IGV </option>
          <option value="no_igv">Sin IGV</option>
        </select>
      </div>


      </div>

      <div class="col-md-12">
        <a href="${url_edit}" class="btn btn-xs btn-default btn-block"> Modificar </a>
      </div>
      <div class="col-md-12">
        <a href="${url_anular}" class="btn btn-xs btn-default btn-block bg-red anular-btn"> Anular </a>
      </div>
      <div class="col-md-12">
        <a href="#" target="self" data-id="${id}" class="btn btn-xs btn-default btn-block bg-red eliminate-element"> Eliminar </a>
      </div>
    </div>

    </div>
    `

    ///

    const $title = $modal.find('.modal-title');
    const $body = $modal.find('.modal-body');

    $title.empty()
    $title.addClass('text-center');
    $title.append(title);

    $body.empty()
    $body.append(body);

    $modal.modal();

    console.log("mostar informacion");

    e.preventDefault();
    return false;

  });




}

function init() {
  initDatable();
  events();
  headerAjax();
  initTags();
}

$(document).ready(init);
