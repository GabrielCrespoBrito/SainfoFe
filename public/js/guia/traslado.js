function isSalida()
{
  return $("[name=tipo_movimiento] option:selected").val() === "S";
}

function initDatable() {

  let url_search = $('#datatable').attr('data-url');;

  let columns = [
    { data: 'nrodocumento' },
    { data: 'correlativo', searchable: false, orderable: false     },
    { data: 'GuiFemi' , searchable : false, orderable : false      },
  ];

  if (isSalida()) {
    columns.push(
      { data: 'estado_traslado', searchable: false, orderable: false },
      { data: 'guia_traslado', searchable: false, orderable: false }
      )
    }
  else {
    columns.push(
      { data: 'estado_conformidad', searchable: false, orderable: false },
      { data: 'observaciones', searchable: false, orderable: false },
      { data: 'guia_traslado', searchable: false, orderable: false },      
    );
  }


  window.table = $('#datatable').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    createdRow: function (row, data, index) {
      // console.log("createdRow", row, arguments);
      $(row).data('info', data);
    },       
    "ajax": {
      "url": url_search,
      "data": function (d) {
        return $.extend({}, d, {
          "tipo": $("[name=tipo_movimiento] option:selected").val(),
          "mes": $("[name=mes] option:selected").val(),
          "local": $("[name=local] option:selected").val(),
          "estado_ingreso": $("[name=estados_ingresos] option:selected").val(),
          "estado_salida": $("[name=estados_salidas] option:selected").val(),
        });
      },
    },
    "columns": columns
  });
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

function showHideEstadoSelect() 
{
  let is_ingreso = $("[name=tipo_movimiento] option:selected").val() == "I";
  let $select_estados_ingresos = $(".estados_ingresos");
  let $select_estados_salidas = $(".estados_salidas");
  
  if( is_ingreso ){
    $select_estados_salidas.hide();
    $select_estados_ingresos.show(500);
  }

  else {
    $select_estados_ingresos.hide();
    $select_estados_salidas.show(500);
  }

  // I want to fucking dream, what do you think about
}

function openModalTraslado(e)
{
  e.preventDefault();
  console.log("openModalTras")
  $("#modalTraslado").modal();
  window.guia_id = $.trim($(this).parents('tr').find(':eq(0)').text());
  return false;  
}

function openModalConformidad(e) {
  e.preventDefault();
  let $tr = $(this).parents('tr');
  let data = $tr.data('info');
  console.log("openModalConformidad-dataRow" , $tr ,  data );
  let $modal =$("#modalComformidad");
  $modal.find('input[name=e_conformidad]').filter(`[value=${data.e_conformidad}]`).prop('checked', true);
  $modal.find('[name=obs_traslado]').val(data.obs_traslado);
  window.guia_id = $.trim($(this).parents('tr').find(':eq(0)').text());
  $modal.modal();
  return false;
}

function guardarGuia()
{
  let almacen = $("[name=almacen_id] option:selected").val();
  let local = $("[name=local] option:selected").val();

  if( almacen == local ){    
    notificaciones('El traslado tiene que ser a un almacen diferente al de la guia de salida', 'error', '');
    return;
  }

  else {    
    let url = $("#modalTraslado form").attr('data-route');
    url = url.replace('@@@@' , guia_id );

    $("#modalTraslado form").attr('action', url );
    $(".load_screen").show();

    let funcs = {
      success:  function(data) {
        console.log("success" , data)
        notificaciones(  'Traslado al almacen exitoso' , 'success');
        $("#modalTraslado").modal('hide');
        table.draw();
      }, 
      complete : function(data){
        console.log("complete" ,data );
        $(".load_screen").hide();
      }
    };

    let data = $("#modalTraslado form").serialize();
    data += "&json_response=1";
    ajaxs( data , url , funcs );
  }

}


function guardarConformidad(e) 
{
  e.preventDefault()

  let url = $("#modalComformidad form").attr('action');
  url = url.replace('@@@@', guia_id);

  $(".load_screen").show();

  let funcs = {
    success: function (data) {
      notificaciones('Información Guardada', 'success');
      $("#modalComformidad").modal('hide');
      table.draw();
      // ------------------
    },
    complete: function (data) {
      $(".load_screen").hide();
    }
  };

  let data = {
    'e_conformidad': $("[name=e_conformidad]:checked").val(),
    'obs_traslado': $("[name=obs_traslado]").val(),
  }
  
  ajaxs( data , url, funcs );

  return false;
}


function events()
{
  // Tipo Movimiento
  $("[name=tipo_movimiento]").on('change', function () {
    location.href = $(this).find('option:selected').attr('data-route')    
  })
  
  // Local
  $("[name=local],[name=estados_salidas],[name=estados_ingresos]").on('change', function () {
    table.draw();
  })

  // Table
  $("table").on('click', '.trasladar-guia', openModalTraslado)
  $("table").on('click', '.modal-conformidad', openModalConformidad)  
  $(".aceptar_guia").on('click', guardarGuia)
  $("#modalComformidad form").on('submit', guardarConformidad )
}

init(initDatable, events);

Helper__.init(showHideEstadoSelect);
