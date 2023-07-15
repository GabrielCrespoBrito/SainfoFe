
let en_proceso = false;
let trs_enviar = [];

function documento_check( value , display , data , settings )
{
  function check(resp){
    let resp_bool = Number(resp);
    return resp_bool ? "<span class='fa fa-check-square-o'></span>" : "<span class='fa fa-square-o'></span>";    
  }
  let col = settings.col;

  if( value ){
    if(col == 2) return check(value.XML);
    if(col == 3) return check(value.PDF);
    if(col == 4) return check(value.CDR);
  }

  else {
    return check(0);
  }
}

function verificar_estado( value , display , data , settings )
{
  cl("settings",settings);

  let tr = $(settings.settings.aoData[ settings.row ].nTr);
  if( value ){
    if( value.XML && value.PDF && value.CDR ){
      tr.addClass('estado_ok');
      return "<span class='fa fa fa-check'></span> Todo respaldado";  
    }
    tr.addClass('estado_faltante');    
    return "<span class='fa fa-circle-o'></span> Con documentos faltantes";
  }
  tr.addClass('estado_por_enviar');
  return "<span class='fa fa-spin fa-spinner'></span> Por respaldar";
}


function initDatatable()
{
  table = $('#datatable').DataTable({
    autoFill: true,
    language : {
      processing: "Buscando...",
      paginate: {
        first:      "Primera",
        previous:   "Anterior",
        next:       "Siguiente",
        last:       "Ultima"
      }
    },
    "processing"   : true,
    "serverSide"   : true,
    // "lengthChange" : false,
    "ordering"     : true,  
    "ajax": { 
      "url" : url_consulta,  
      "data": function ( d ) {
       return $.extend( {}, d, {
         "estado": $("[name=estado]").val(),
       });
      }
    },
    "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
    "initComplete" : function initComplete(settings, json){         
      $('div.dataTables_filter input').attr('placeholder', 'Buscar');
    },
    "columns" : [
      { data : 'VtaOper' },
      { data : 'VtaNume' },
      { data : 'nube' , render : documento_check },
      { data : 'nube' , render : documento_check },
      { data : 'nube' , render : documento_check },
      { data : 'nube' , render : verificar_estado },      
    ]
  });
}

function function_event()
{
  table.draw();
}

function data_actual()
{
  let tr_actual = trs_enviar.first();
  return {
    'id_factura' : $(tr_actual).find("td:eq(0)").text()
  }
}

function respaldar_documentos(e)
{
  console.log("respaldar" , e);
  e.preventDefault();

  if(en_proceso){
    console.log("en proceso");
    return;
  }

  let trs = $("#datatable tbody tr.select");    

  if( trs.length ){
    en_proceso = true;
    trs_enviar = trs.toArray();
    guardar_documentos()
  }
  
}


function guardar_documentos()
{
  $(".btn-procesando").show();
  $(".block_elemento").show();

  let data = data_actual();
  let funcs = {
    complete : completado_envio,
    error : function(d){
      console.log("error", d);
    }
  };

  ajaxs( data , url_respaldar , funcs );
}

function completado_envio(data)
{
  // cl("data", data );
  // return
  let mensaje = data.responseText;   
  notificaciones(mensaje , data.status);
  
  $(trs_enviar.first())
  .removeClass('select')
  .remove();

  trs_enviar.shift();

  if( trs_enviar.length ){
    guardar_documentos();
  }

  else {
    $(".btn-procesando").hide();
    $(".block_elemento").hide();      
    en_proceso = false;
    table.draw();
  }
}


function events()
{
  $("[name=estado]").on('change' , function_event );
  $(".respaldar").on('click' , respaldar_documentos );
  eventos_predeterminados('table_select_tr' , '#datatable' , [true] ) 

  
  // eventos_predeterminados('table_datatable' , '#datatable' )   
}


// Iniciadora
init(
  events,
  initDatatable
)



