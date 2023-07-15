var popover_current = null;

function borrar_estilos(){}
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

function checkedDatos(data,type,row,meta)
{ 
  let valor = Number(data); // 1 - 0
  let icon = ['fa fa-square-o', 'fa fa-check-square-o']
  let info = {
    13 : {
      'clas' : 'accion_xml',
    },
    14 : {
      'clas' : 'accion_pdf',      
    },
    15 : {
      'clas' : 'accion_cdr',      
    },
    16 : {
      'clas' : 'accion_email',      
    },
  };

  let button = "<a class='btn btn-default btn-xs " + info[meta.col].clas + "'>" +
   "<span class='" + icon[valor] + "'> </span></a>";

  return button;  
};

function defaultErrorAjaxFunc(data){
  console.log( "error ajax" , data.responseJSON );
  let errors = data.responseJSON.errors;
  let mensaje = data.responseJSON.message;
  let erros_arr = [];
  for( prop in errors ){
    for( let i = 0; i < errors[prop].length; i++  ){
      erros_arr.push( errors[prop][i] );
      // let form  = $( form_accion );
      // poner_error( $( form_accion + " input").filter("[name=" + prop  + "]") );
    }
  }
  console.log("error" , erros_arr , mensaje );
  // console.log("error del ajax" , data );
  notificaciones( erros_arr , 'error' , mensaje ); 
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

function fixedNumber(data,type,row,meta){
  return Number(data).toFixed(2);
}

function initDatable()
{

  table = $('#datatable').DataTable({
    "pageLength": 25,
    "createdRow": function( row, data, dataIndex ){
      // if ( data.VtaEsta == "A" ) {
        // $(row).addClass( 'boleta_anulada' );
      // }
    },
    "responsive" : true,
    "processing" : true,
    "serverSide" : true,
    "order": [[ 0, "desc" ]],
    "ajax": { 
      "url" : url_venta_consulta,  
      "data": function ( d ) {
       return $.extend( {}, d, {
         "buscar_por_fecha": $("[name=buscar_por_fecha]").val(),        
         "fecha_desde": $("[name=fecha_desde]").val(),
         "fecha_hasta": $("[name=fecha_hasta]").val(),
         "tipo"       : $("[name=tipo] option:selected").val(),
         "hoy"        : $("[name=hoy]").val(),

       });
      }
    },
    "columns" : [      
      { data : 'VtaOper' },
      { data : 'TidCodi' },
      // { data : 'VtaSeri' },
      { data : 'VtaNume' , render : function(data,type,row,meta){
        return row.VtaSeri + "-" + row.VtaNumee;
      }},
      { data : 'VtaFvta' },         
      { 
        data : 'cliente.PCNomb',
        className : 'clinte_nombre line-d' , 
        render : function(data,type,row, meta){
        let str =  data.slice(0,15) + "...";
        return "<span title='" + data +"'>" + str + "</span>";
        }
      },  
      { data : 'moneda.monabre' , searchable: false },
      { data : 'VtaImpo' , render : fixedNumber , searchable: false },
      { data : 'VtaPago' , render : fixedNumber , searchable: false},
      { data : 'VtaSald' , render : fixedNumber , searchable: false },
      { data : 'estado' , searchable: false , className: 'estado' },      
      { data : 'btn' , searchable: false , className: 'estado' }
    ]
  });
}

function initPicker( format = "yyyy-mm-dd" )
{
  $('[name=fecha_desde],[name=fecha_hasta]').datepicker({
    autoclose: true,
    format: format,
  });
}

function eliminar_ele()
{
  if( confirm("Desea quitar el Documento?") ){
    let data = { id_factura : $(".seleccionado td:eq(0)").text()} ;
    let funcs = {
      success : borradoExitoso
    }
    ajaxs( data , url_eliminar_factura  , funcs );
  }
}


function showModalRedactarCorreo()
{  
  // console.log(".accion_email");
  $("#modalRedactarCorreo").modal();
  return false;
}

function buscar_table()
{
  $("[name=buscar_por_fecha]").val(1);    
  table.draw();
}

function borradoExitoso(data){
  let tr_factura = $(".seleccionado");
  notificaciones("Factura eliminado exitosamente", "success")
  tr_factura.css('outline' , '2px solid red');
  tr_factura.hide(1000 , function(){
    active_or_disable_(false,tr_factura)
    active_ordisable_tr(false)
    tr_factura.remove();
    table.draw(); 
  });  
}


function anulado_exitoso(data)
{
  console.log("anulado exitoso",data);
  table.draw();
}

function anular_documento()
{
  let select = $(".seleccionado");

  if( select.find("td:eq(1)").text() == "03" ){
    if(select.find("td:eq(11)").text() != "A" ){
      if(confirm("Seguro que desea anular esta boleta?")){
        ajaxs( { id_boleta : select.find("td:eq(0)").text() } , url_anular_boleta , { success : anulado_exitoso }  )
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


function events()
{
  $(".buscar_factura_b").on('click' , buscar_table );   
  $(".eliminar-accion").on( 'click' , eliminar_ele );   

  // $("*").on("click",".accion_email" , showModalRedactarCorreo);

  $(".anular_documento").on('click' , anular_documento );
  $(".anular-accion").on('click' , anular_documento );
  $("[name=tipo]").on('change' , function(){table.draw()});


  table.on('draw.dt', function(){
    var $btns = $("[data-toggle='popover-x']");
    if( $btns.length ){
      $btns.popoverButton({
        closeOpenPopovers: true,
        keyboard : false,
        backdrop : false,
        trigger : 'click'
      });
    }
  });
}

function init()
{
  borrar_estilos();
  initPicker();
  initDatable();  
  events();
  setTimeout( borrar_estilos , 1000 );
}

$(document).ready(init);