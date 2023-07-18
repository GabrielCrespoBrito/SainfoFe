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
  return window.tipo_doc ? window.tipo_doc : 'todos';
}

function initDatable() {

  table = $('.datatable').DataTable({
    "searching":false,
    "pageLength": 50,
    "lengthChange": false,
    "responsive": true,
    "processing": false,
    "ordering" : false,
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
          "buscar_por_fecha": 1,
          "fecha_desde": getFechaInicio(),
          "fecha_hasta": getFechaFinal(),
          "tipo": getTipoDoc(),
          "status": getStatus(),
          "local": "todos",
        });
      }
    },
    "columns": [
      { data: 'VtaFvta', sorting: false, orderable: false, searchable: false },
      { data: 'nro_venta', sorting: false, orderable: false, searchable: false },
      { data: 'TidCodi', orderable: false, searchable: false },
      { data: 'VtaSeri', sorting: false, orderable: false, searchable: false },
      { data: 'VtaNumee', sorting: false, orderable: false, searchable: false },
      { data: 'PCNomb', orderable: false, searchable: false, render: function (data) { return data.slice(0, 15).concat("...") } },
      { data: 'monabre', orderable: false, searchable: false },
      { data: 'VtaImpo', orderable: false, searchable: false, render: fixedNumber, className: 'text-right-i' },
      { data: 'VtaEsta', orderable: false, searchable: false },
      { data: 'fe_estado', orderable: false, searchable: false },
      { data: 'fe_rpta', orderable: false, searchable: false },
      { data: 'fe_obse', orderable: false, searchable: false },
      { data: 'VtaFMail', orderable: false, searchable: false },
      { data: 'estado_sunat', orderable: false, searchable: false, className: 'estado' },
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


function searchTable()
{
  table.draw();
}

function setFechas()
{
  const mescodi = $("[name=mes]").val();
  const year = mescodi.substring(0, 4);
  const mes = mescodi.substring(4, 6);
  const fecha_inicio = `${year}-${mes}-01`;
  let date_today = new Date(fecha_inicio)
  let lastDay = new Date(date_today.getFullYear(), mes, 0);

  window.fecha_inicio = fecha_inicio;
  let diaFinal = lastDay.getDate();
  diaFinal = diaFinal < 10 ? '0'.concat(diaFinal) : diaFinal;
  window.fecha_final = `${year}-${mes}-${diaFinal}`;
}

function setTipo(tipo_doc) {
  window.tipo_doc = tipo_doc;
}

function setStatus(status_doc) {
  window.status_doc = status_doc;
}

$("[name=mes]").on('change', () => {
  searchUltimaBusqueda();
  setFechas();
})

function showDateInfo(show = true, date = null)
{
  if(show){
    console.log(date)
    $(".date-update").show();
    date = date ? 'Ult. Fecha de Consulta: <strong>' + date + "</strong>" : 'No se ha consultado todavia';
    $(".date-update").find('.value').empty().html(date);
  }
  else {
    $(".date-update").hide();
  }
}


function searchUltimaBusqueda()
{

  $("[name=mes]").prop('disabled', true)

  const data = {
    data : $("[name=mes]").val()
  };

  const url = $(".btn-filtro-change.active").attr('data-url');

  const funcs = {
    success: responde => showDateInfo(true, responde.date)
    ,
    complete : () => {
      $("[name=mes]").prop('disabled', false)
    }
  }

  ajaxs( data, url , funcs );
}

$("body").on('click', ".generate-report", (e) => {

  let $btn = $(e.target)
  let url = $btn.attr('data-url');
  let status = getStatus();
  let params = new URLSearchParams();

  params.set('formato', $("[name=formato]").val());
  params.set('estado_sunat', status ? status : 'todos' );
  params.set('fecha_inicio', getFechaInicio());
  params.set('fecha_final', getFechaFinal());
  params.set('tipo', getTipoDoc());
  url = url.concat( '?', params.toString());
  $btn.attr('href', url )
})


$("body").on('click', '.btn-status-change', (e) => {
  
  var $btn = e.target.tagName.toLowerCase() === 'a' ? $(e.target) : $(e.target).parent()
  e.preventDefault()

  if( $btn.is('.active') ){
    return false;
  }

  $('.btn-status-change').removeClass('active');
  $btn.addClass('active');    
  
  const status = $btn.attr('data-status') == "all" ? null : $btn.attr('data-status');
  const tipo_doc = $btn.attr('data-tipo');
  setStatus(status)
  setTipo(tipo_doc)

  console.log(status, tipo_doc);
  searchTable();
});


$(".search-consulta").on('click', (e) => {

  e.preventDefault();
  let $btn = $(e.target);

  $("#load_screen").show();

  const url = $btn.attr('data-url');

  const data = {
    tipo: $(".btn-filtro-change.active").attr('data-tipo'), 
    mes: $("[name=mes]").val(),
    fecha_desde:  getFechaInicio(),
    fecha_hasta: getFechaFinal(),
    consult: Number($("[name=consult]").is(':checked')),
  };

  const funcs = {
    success: (html) => {
      $(".reporte-data").empty();
      $(".reporte-data").append(html);
      initDatable();
    },
    complete : () => {
      $("#load_screen").hide();
    }
  }

  ajaxs( data , url , funcs ); 
  return false;
});



init(datepicker, setFechas, searchUltimaBusqueda )



