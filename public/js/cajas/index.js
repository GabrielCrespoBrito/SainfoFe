  // notificaciones
function notificaciones ( mensaje , type = 'info' , heading = '' ){
  var info = {
    'heading'   : heading,
    'position'  : 'top-center',
    'hideAfter' : 3000, 
    'showHideTransition' : 'slide' 
  };

  $.toast({
    heading   : info.heading,
    text      : mensaje,
    position  : info.position,
    showHideTransition : info.showHideTransition, 
    hideAfter : info.hideAfter,
    icon      : type,
    stack: false
  });
};

function defaultErrorAjaxFunc(data){
  // console.log( "error ajax" , data.responseJSON );
  let errors = data.responseJSON.errors;
  let mensaje = data.responseJSON.message;
  let erros_arr = [];
  for( prop in errors ){
    for( let i = 0; i < errors[prop].length; i++  ){
      erros_arr.push( errors[prop][i] );
    }
  }
  console.log("error" , erros_arr , mensaje );
  notificaciones( erros_arr , 'error' , mensaje ); 
}

function headerAjax(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });   
}

function ajaxs( data , url , funcs = {} )
{  
  funcs.mientras ? funcs.mientras() : null;
  $.ajax({
    type : 'post',
    url  : url,  
    data : data,
    success : function(data){   
      funcs.success ? funcs.success(data) : defaultSuccessAjaxFunc(data);
    },
    error : function(data){
      funcs.error ? funcs.error(data) : defaultErrorAjaxFunc(data);       
    },
    complete : function(data){
      console.log("ajax terminado");
      executing_ajax = false;        
      funcs.complete ? funcs.complete(data) : null;
    }
  });  
};  

function initDatable()
{
  table = $('#datatable').DataTable({
    "language": {
      "search": "Buscar",              
      "lengthMenu": "_MENU_",
      "zeroRecords": "Nada encontrado , disculpas",
      "info": "Mostrar pagina _PAGE_ de _PAGES_",
      "infoEmpty": "No ay cajas disponibles",
      "loadingRecords": "Cargando...",
      "processing":     "Procesando...",
      "previous": "Anterior",
      "infoFiltered": "(filtrado de _MAX_ registros totales)",
      "next": "Siguiente",
      "paginate": {
        "first":      "Primera",
        "last":       "Ultima",
        "next":       "Siguiente",
        "previous":   "Anterior"
      },
    },
    "responsive" : true,
    "processing" : true,
    "serverSide" : true,
    "order": [[ 0, "asc" ]],
    "ajax": { 
      "url" : url_consulta,   
      "data": function (d) {
       return $.extend( {}, d , {
        "fecha"    : $("[name=fecha]").val(),
        "local"    : $("[name=local]").val(),      
        "usuario"  : $("[name=usuario]").val(),             
       });
      }
    },
    "columns" : [      
      { data: 'column_link' , className : 'id'},
      { data : 'CajFech' },
      { data : 'CajFecC' },      
      { data : 'CajSalS' }, 
      { data : 'CajSalD' },      
      { data : 'CajEsta' , className : "estado" , render : function(value,b,data,d){
        return (value == "Ap") ? "<span class='aperturada'> Aperturada </span>" : "<span class='cerrado'> Cerrada </span>"        
      }},      
      { data : 'User_FModi' },      
      { data : 'User_Crea' },      
    ]
  });
}


function buscar_table()
{
  table.draw();

  desactivar_button( "#cerrar", "#eliminar" , "#eliminar");
}


function activar_button()
{
  for( let i = 0; i < arguments.length; i++ ){
    $(arguments[i]).removeClass('disabled');
  }
}

function desactivar_button()
{
  for( let i = 0; i < arguments.length; i++ ){
    $(arguments[i]).addClass('disabled');
  }
}

function seleccionar_elemento()
{
  let tr = $(this);

  tr.parents('tbody').find("tr").not(tr).removeClass('seleccionado');

  if( tr.find('.dataTables_empty').length  ) {         
    return;
  }

  if( tr.is('.seleccionado')) {    
    desactivar_button( "#cerrar", "#resumen" , "#eliminar", "#movimiento");
  }

  // Poner selección
  else {

    activar_button( "#resumen" , "#eliminar" , "#cerrar", "#movimiento" );
    
    let href = $("#resumen").data('href').replace("xxx" , tr.find("td:eq(0)").text() );
    let href_mov = $("#movimiento").data('href').replace("xxx" , tr.find("td:eq(0)").text() );

    $("#resumen").attr('href' , href );
    $("#movimiento").attr('href' , href_mov );


    
    if(  tr.find("td:eq(5)").text().trim().toLowerCase() == "aperturada" ){
      activar_button("#cerrar");
    }

    else {
      desactivar_button("#cerrar");
    }

  }    

  tr.toggleClass('seleccionado');
}


function show_caja(data , mensaje = false , tipo = false ){

  if(mensaje){
    notificaciones(mensaje,tipo);
  }

  buscar_table();
}



function cerrar_caja()
{  
  console.log("cerrar_caja");

  if (confirm("Esta seguro de cerra la caja?")) {

    ajaxs( { id_caja : $('.seleccionado td:eq(0)').text() } , url_cerrar , { success : show_caja } ) ;      
  }

}


function aperturar_caja()
{
  let seleccionado = $(".seleccionado");

  if( seleccionado.length  ){

    ajaxs( { id_caja : $('.seleccionado .id').text() } , url_reaperturar , { success : show_caja } );

  }

  else {

    ajaxs( { id_local : $('[name=local] option:selected ').val() } , url_aperturar , { success : show_caja } );
  }

}


function eliminar_caja()
{
  let seleccionado = $(".seleccionado");

  if( seleccionado.length  ){

    if(confirm('Esta seguro de eliminar esta caja?' )){

      ajaxs( { id_caja : $('.seleccionado .id').text() } , url_eliminar , { success : show_caja } );

    }

  }



}


function events()
{
  $("#datatable").on( 'click' , 'tbody tr', seleccionar_elemento );
  $("#aperturar").on( 'click' , aperturar_caja );
  $("#eliminar").on( 'click' , eliminar_caja );  
  $("#cerrar").on( 'click' , cerrar_caja );  
  $("[name=fecha],[name=usuario],[name=local]").on( 'change' , buscar_table );   
}

function init()
{  
  initDatable();  
  events();
  headerAjax();
}

$(document).ready(init);