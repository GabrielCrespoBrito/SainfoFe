id_guia = null;
en_proceso = false;

function seleccionar_tr() {

  if (en_proceso) {
    console.log("en proceso");
    return;
  }

  let $tr = $(this);
  let s = 'seleccionado';

  $tr.is('.'.concat(s)) ? $tr.removeClass(s) : $tr.addClass(s)
}

function initDatable() {
  let url_search = $('#datatable').attr('data-url');;

  let columnEstado = {
    data: 'estado',
    searchable: false,
    sortable: false
  };
  let columnAccion = {
    data: 'accion',
    searchable: false,
    sortable: false
  };

  let columns = [
    { data: 'nrodocumento' },
    {
      data: 'GuiNume', render: function (param1, param2, param3, param4) {
        let data = param3;
        let nume = data.GuiSeri == null ? data.GuiNume : (data.GuiSeri + "-" + data.GuiNumee);
        // console.log( "data guia" , data , nume  );
        return nume;
      }
    },
    { data: 'docrefe' },
    { data: 'GuiFemi' },
    { data: 'cli.PCNomb' },
    {
      data: 'almacen',
      render: function (param1, param2, param3, param4) {
        return param1 == null ? '' : param1.LocNomb
      },
      searchable: false,
      sortable: false
    }
  ];

  if (false) {
    columns.push(columnEstado)
  }

  // columns.push(columnAccion)

  window.table = $('#datatable').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": url_search,
      "data": function (d) {
        return $.extend({}, d, {
          "mes": $("[name=mes]").val(),
          "formato": $("[name=formato]:checked").val(),
        });
      }
    },
    "columns": columns
  });
}

function data_actual() {
  let tr_actual = trs_enviar.first();
  return {
    'id_guia': $(tr_actual).find("td:eq(0)").text()
  }
}

function completado_envio(data) {
  console.log("respuesta del envio", data);

  let mensaje = "";

  if (data.responseJSON) {

    if (data.responseJSON.data) {
      mensaje = data.responseJSON.data;
    }

    else if (data.responseJSON.errors) {
      if ($.isArray(data.responseJSON.errors)) {
        mensaje = data.responseJSON.errors.error[0];
      }
      else {
        //
        let errors = data.responseJSON.errors;
        for (prop in errors) {
          for (let i = 0; i < errors[prop].length; i++) {
            mensaje += " " + errors[prop][i];
          }
        }
        //
      }
    }
    else {
      mensaje = data.responseJSON.message;
    }


  }
  else {
    mensaje = "Error";
  }

  $(trs_enviar.first()).removeClass('seleccionado');

  if (data.status == 200) {
    notificaciones(mensaje, "success");
    $(trs_enviar.first()).remove();
  }
  else {
    let message_code = mensaje;
    notificaciones(message_code, "error");
  }

  trs_enviar.shift();

  if (trs_enviar.length) {
    enviar_facturas();
  }
  else {
    activar_button(".enviar-sunat", "#select_all");
    $(".btn-procesando").hide();
    $(".block_elemento").hide();
    en_proceso = false;
    table.draw();
  }
}

function notificaciones(mensaje, type = 'info', heading = '', infoNew = {}) {

  const infoDefault = {
    'heading': heading,
    'position': 'top-center',
    'hideAfter': 999999,
    'showHideTransition': 'slide'
  };

  const info = Object.assign({}, infoDefault, infoNew)

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


function enviar_facturas() {
  console.log("ENVIANDO FACTURAS");

  desactivar_button(".enviar-sunat", "#select_all");
  $(".btn-procesando").show();
  $(".block_elemento").show();

  let data = data_actual();
  data.id_guia = $.trim(data.id_guia);

  // console.log( "data" , data );
  let url = url_enviar_sunat.replace('@@', data.id_guia);

  let funcs = {
    complete: completado_envio,
    error: function (d) {
      console.log("error", d);
    }
  };

  ajaxs(data, url, funcs);
}

function enviar_sunat(e) {
  e.preventDefault();

  if (en_proceso) {
    console.log("en proceso");
    return;
  }

  let trs = $("#datatable tbody tr.seleccionado");

  if (trs.length) {
    en_proceso = true;
    trs_enviar = trs.toArray();
    enviar_facturas()
    console.log("trs_seleccionados", trs.length)
  }
}


function toggleSelect(e) {

  e.preventDefault();

  if (en_proceso) {
    console.log("en proceso");
    return;
  }

  let $trs = $("#datatable tbody tr");
  if (select_all) {
    $trs.removeClass('seleccionado');
    select_all = false;
  }
  else {
    $trs.addClass('seleccionado');
    select_all = true;
  }
}

function events() {
  $("#datatable").on('click', "tbody tr", seleccionar_tr);
  $("#select_all").on('click', toggleSelect);
  $(".enviar-sunat").on('click', enviar_sunat);
}

init(initDatable, events);
Helper__.init();