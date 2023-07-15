window.fecha_inicio = null;
window.fecha_final = null;
window.status_doc = null;
window.tipo_doc = null;


function getFechaInicio()
{
  return window.fecha_inicio;
}

function getFechaFinal()
{
  return window.fecha_final;
}

function getStatus()
{
  return window.status_doc;
}

function getTipoDoc()
{
  return window.tipo_doc;
}

function initDatable() {

  table = $('.datatable').DataTable({
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
      "url": $('.datatable').attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "buscar_por_fecha": $("[name=buscar_por_fecha]").val(),
          "fecha_desde": getFechaInicio(),
          "fecha_hasta": getFechaFinal(),
          "tipo": getStatus(),
          "status": getTipoDoc(),
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
      { data: 'PCNomb', orderable: false, searchable: false, render: function (data) { return data.slice(0, 15).concat("...") } },
      { data: 'monabre', orderable: false, searchable: false },
      { data: 'VtaImpo', orderable: false, searchable: false, render: fixedNumber, className: 'text-right-i' },
      { data: 'VtaPago', orderable: false, searchable: false, render: fixedNumber, className: 'text-right-i' },
      { data: 'VtaSald', orderable: false, searchable: false, render: fixedNumber, className: 'text-right-i' },
      { data: 'alm', orderable: false, searchable: false },
      { data: 'estado', orderable: false, searchable: false, className: 'estado' },
      { data: 'btn', orderable: false, searchable: false }
    ]
  });


  window.table_ventas = table;

}


//



function datepicker()
{
  $('.datepicker').datepicker({
    autoclose: true,
    language: 'es',
    format: 'yyyy-mm-dd'
  });
}

$(".btn-filtro-change").on('click', (e) => {

  e.preventDefault();
  let $btn = $(e.target);

  if(!$btn.is('active')){
    let tipo = $btn.attr('data-tipo');
    $(".btn-filtro-change").removeClass('active');
    $btn.addClass('active');
    $(".filtro_temporalidad").hide();
    console.log({tipo})
    $(".filtro_temporalidad").filter('[data-tipo=' + tipo  + ']').show();
  }

});


$(".search-consulta").on('click', (e) => {

  e.preventDefault();
  let $btn = $(e.target);

  $("#load_screen").show();

  const url = $btn.attr('data-url');

  const data = {
    tipo: $(".btn-filtro-change.active").attr('data-tipo'), 
    mes: $("[name=mes]").val(),
    fecha_desde: $("[name=fecha_desde]").val(),
    fecha_hasta: $("[name=fecha_desde]").val(),
  };

  const funcs = {
    success: (html) => {
      $(".reporte-data").empty();
      $(".reporte-data").append(html);

      initDatable();
      // $(".datatable").Datatable();

    },
    complete : () => {
      $("#load_screen").hide();
    }
  }

  ajaxs( data , url , funcs ); 
  // search - consulta
  // if (!$btn.is('active')) {
  //   let tipo = $btn.attr('data-tipo');
  //   $(".btn-filtro-change").removeClass('active');
  //   $btn.addClass('active');
  //   $(".filtro_temporalidad").hide();
  //   console.log({ tipo })
  //   $(".filtro_temporalidad").filter('[data-tipo=' + tipo + ']').show();
  // }


  return false;
});



init( datepicker )



