window.fecha_inicio = null;
window.fecha_final = null;
window.status_doc = null;
window.tipo_doc = null;


function isFechaByMensual()
{
  return $(".btn-filtro-change.active").is('[data-tipo=mes]');
}

function initSelectTable()
{
  $(".datatable").on('click', 'tbody tr', seleccionar_tr);    
}



function getFechaInicio()
{
  return isFechaByMensual() ? window.fecha_inicio : $("[name=fecha_desde]").val();
}

function getFechaFinal()
{
  return isFechaByMensual() ? window.fecha_final : $("[name=fecha_final]").val();
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

  const empresa_id = $('.datatable').attr('data-id');
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
          "empresa_id" : empresa_id,
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

  initSelectTable();
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
    $(".filtro_temporalidad").filter('[data-tipo=' + tipo  + ']').show();
    showDateInfo(tipo == "mes")
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
  if( !isFechaByMensual() ){
    return false;
  }

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



function active_ordisable_trfactura(active = true, tr_factura) {

  if (active) {
    $(tr_factura).addClass('seleccionado');
  }

  else {
    $(tr_factura).removeClass('seleccionado');
  }
}

function seleccionar_tr() {
  let tr = $(this);

  if (tr.find('.dataTables_empty').length) {
    return;
  }

  if (tr.is('.seleccionado')) {
    active_ordisable_trfactura(false, tr);
  }

  else {
    active_ordisable_trfactura(true, tr);
  }
}

$("body").on('click', ".generate-report", (e) => {

  let $btn = $(e.target)
  let url = $btn.attr('data-url');
  let status = getStatus();
  let params = new URLSearchParams();
  
  const formato = $("[name=formato]").val();


  params.set('formato', formato);
  params.set('estado_sunat', status ? status : 'todos' );
  params.set('fecha_inicio', getFechaInicio());
  params.set('fecha_final', getFechaFinal());
  params.set('tipo', getTipoDoc());


  if( formato == "archivos" ){
    let trSelect = $("tr.seleccionado");

    let message = false; 

    if ( trSelect.length == 0 ){
      message = 'Para Descargar los Archivos, tiene que seleccionan los documentos. No se permiten mas de 50 Documentos';
    }
    
    if (trSelect.length > 50) {
      message = 'No se permiten los archivos de mas de 50 Documentos a la ves';
    }

    if( message ){
      notificaciones( message );
      e.preventDefault();
      return false;
    }

    let ids = [];
    
    trSelect.each(function(index,dom){
      ids.push( $(dom).find( 'td:eq(1)' ).text().trim() )
    })

    params.set('ids', ids);
  }

  url = url.concat('?', params.toString());
  $btn.attr('href', url)

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


$("body").on('click', '.show-totales', (e) => {

  console.log("hola")
  const $ele = $(e.target);

  $(".totales-reporte").filter("[data-codi=" +  $ele.attr('data-codi')  +"]").toggle();

})

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
      Helper.init();
      searchUltimaBusqueda();
    },
    complete : () => {
      $("#load_screen").hide();
    }
  }

  ajaxs( data , url , funcs ); 
  return false;
});




init(datepicker, setFechas, searchUltimaBusqueda )



