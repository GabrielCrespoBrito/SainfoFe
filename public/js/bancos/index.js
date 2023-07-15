
function setCuentasData() {
  let cuentas = $(this).find('option:selected').data('cuentas');
  add_to_select("[name=cuenta_id]", cuentas, 'CueCodi', "CueNume", true)
}



function cerrar_caja() {
  if (confirm("Esta seguro de cerra la caja?")) {
    
    let id = $(this).parents('tr').find('td:eq(0)').text();

    console.log("id" , id);

    let data = { 
      id_caja: id 
    }
    let funcs =  {
      success : function (data){ 
        table.draw(); notificaciones(data.message) 
      }
    }

    ajaxs( data, url_cerrar, funcs );
  }
}


function reaperturar () {
  let data = {
    id_caja : $(this).parents('tr').find('td:eq(0)').text()
  }

  let funcs =
  {
    success: (data) => {
      notificaciones(data.message, 'success');
      table.draw()
    }
  }
  console.log("reaperturar", data , url_reaperturar );

  ajaxs(data, url_reaperturar, funcs);
}

function apertura() {
  let data = {
    'cuenta_id': $("[name=cuenta_id] option:selected").val(),
    'periodo_id': $("[name=fecha] option:selected").val(),
  }

  let funcs = 
  {
    success: (data) => { 
      notificaciones(data.message,'success' );
      table.draw() 
    }
  }

  ajaxs(data, url_apertura, funcs);
}


function eliminar_caja() 
{
  console.log("eliminar_caja")
  
  if (confirm('Esta seguro de eliminar esta caja?')) {
    let data = {
      id_caja: $(this).parents('tr').find('td:eq(0)').text()
    }

    let funcs =
    {
      success: (data) => {
        notificaciones(data.message, 'success');
        table.draw()
      }
    }
    ajaxs(data, url_eliminar, funcs);

  }

}


function initDatable() {
  table = $('#datatable').DataTable({
    "language": {
      "search": "Buscar",
      "lengthMenu": "_MENU_",
      "zeroRecords": "Nada encontrado , disculpas",
      "info": "Mostrar pagina _PAGE_ de _PAGES_",
      "infoEmpty": "No hay apertura de cuenta bancaria disponibles",
      "loadingRecords": "Cargando...",
      "processing": "Procesando...",
      "previous": "Anterior",
      "infoFiltered": "(filtrado de _MAX_ registros totales)",
      "next": "Siguiente",
      "paginate": {
        "first": "Primera",
        "last": "Ultima",
        "next": "Siguiente",
        "previous": "Anterior"
      },
    },
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, "asc"]],
    "ajax": {
      "url": url_consulta,
      "data": function (d) {
        return $.extend({}, d, {
          "periodo_id": $("[name=fecha] option:selected").val(),
          "cuenta_id": $("[name=cuenta_id] option:selected").val(),
        });
      }
    },
    "columns": [
      { data: 'column_link', className: 'id' },
      { data: 'mes.mesnomb' },
      { data: 'CajFech' },
      { data: 'CajFecC' },
      { data: 'CajSalS' },
      { data: 'CajSalD' },
      {
        data: 'CajEsta', className: "estado", render: function (value, b, data, d) {
          return (value == "Ap") ? "<span class='aperturada'> Aperturada </span>" : "<span class='cerrado'> Cerrada </span>"
        }
      },
      { data: 'User_FModi' },
      { data: 'User_Crea' },
      { data: 'column_accion' },
    ]
  });
}


function tableChange()
{
  table.draw();
}


function events() {

  $("[name=bancos]").on('change', setCuentasData);
  $("#aperturar").on('click', apertura);
  $("table").on('click', '.reaperturar',  reaperturar);
  $("table").on('click', '.cerrar'   , cerrar_caja);  
  $("table").on('click', '.eliminar' , eliminar_caja);
  $("[name=fecha],[name=bancos],[name=cuenta_id]").on('change', tableChange  );
}

function init() {
  events();
  initDatable();
}

$(document).ready(init);