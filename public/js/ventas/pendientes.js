$(document).ready(function(e){

  var tr_selects = [];
  var select_all = false;
  var trs_enviar;
  var en_proceso = false;

  function fixedNumber(data,type,row,meta){
    return Number(data).toFixed(2);
  }

  function initDatable()
  {
    table = $('#datatable').DataTable({
      "pageLength": 25,
      "responsive" : true,
      "processing" : true,
      "serverSide" : true,
      "order": [[ 0, "desc" ]],
      "ajax": { 
        "url" : url_venta_consulta,  
        "data": function (d) {
         return $.extend( {} , d , {
           "pendientes": true,
           "tipo": $("[name=tipo] option:selected").val(),
         });
        }
      },
      "columns" : [      
        { data:  'nro_venta', orderable: false, searchable: false },
        { data : 'TidCodi', orderable: false, searchable: false  },
        { data : 'VtaSeri', orderable: false, searchable: false  },
        { data : 'VtaNumee', orderable: false, searchable: false },
        { data : 'VtaFvta', orderable: false, searchable: false  },
        { data :  'cliente_with', orderable: false, searchable: false , className : 'clinte_nombre line-d' , render : function(data,type,row, meta){
          let val = data ? data.PCNomb : '';
          return val;
        }},  
        { data : 'moneda.monabre', orderable: false, searchable: false },
        { data : 'VtaImpo', orderable: false, searchable: false, className : 'text-right', render : fixedNumber },   
        { data : 'VtaXML' , render : checkedDatos , orderable: false, searchable: false  },
        { data : 'VtaPDF' , render : checkedDatos , orderable: false, searchable: false  },
        { data : 'VtaCDR' , render : checkedDatos , orderable: false, searchable: false  },
        { data:  'VtaEsta', className: 'text-center', orderable: false, searchable: false},
      ]
    });
  }

  function ajaxs( data , url , funcs = {} )
  {  
    executing_ajax = true;
    funcs.mientras ? funcs.mientras() : null;
    $.ajax({
      type : 'post',
      url  : url,  
      data : data,
      success : function(data){   
        funcs.success ? funcs.success(data) : null;
      },
      error : function(data){
        funcs.error ? funcs.error(data) : null;       
      },
      complete : function(data){
        executing_ajax = false;        
        funcs.complete ? funcs.complete(data) : null;
      }
    });  
  };  

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


  function headerAjax(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });   
  }  
 
  function seleccionar_tr(){

    if(en_proceso){
      console.log("en proceso");
      return;
    }

    let $tr = $(this);
    let s = 'seleccionado';

    $tr.is( '.'.concat(s) ) ? $tr.removeClass(s) : $tr.addClass(s) 
  }


  function toggleSelect(e) {

    e.preventDefault();

    if(en_proceso){
      console.log("en proceso");
      return;
    }

    let $trs = $("#datatable tbody tr");
    if(select_all){
      $trs.removeClass('seleccionado');    
      select_all = false;      
    }
    else {
      $trs.addClass('seleccionado');          
      select_all = true;
    }
  }

  Array.prototype.first = function()
  {
    return this[0]
  }

  function data_actual()
  {
    let tr_actual = trs_enviar.first();
    return {
      'id_factura' : $(tr_actual).find("td:eq(0)").text()
    }
  }

  function completado_envio(data)
  {
    console.log("respuesta del envio", data );
    
    let mensaje;

    if(  data.responseJSON ){

      if( data.responseJSON.data ){
      mensaje = data.responseJSON.data;
    }
    else {
      mensaje = data.responseJSON.errors.error[0];
    }
  }
  else {
      mensaje = data.statusText;
  }
    
    $(trs_enviar.first()).removeClass('seleccionado');
    
    if(data.status == 200 ){
      notificaciones(mensaje , "success");
      $(trs_enviar.first()).remove();
    }
    else {
      notificaciones(mensaje, "error");
    }
    
    trs_enviar.shift();

    if( trs_enviar.length ){
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

  function enviar_facturas()
  {
    console.log("ENVIANDO FACTURAS");

    desactivar_button(".enviar-sunat", "#select_all");
    $(".btn-procesando").show();
    $(".block_elemento").show();

    let data = data_actual();
    let funcs = {
      complete : completado_envio,
      error : function(d){
        console.log("error", d);
      }
    };

    ajaxs( data , url_enviar_sunat , funcs );
  }

  function enviar_sunat(e)
  {
    e.preventDefault();
    
    if(en_proceso){
      console.log("en proceso");
      return;
    }


    let trs = $("#datatable tbody tr.seleccionado");    

    if( trs.length ){
      en_proceso = true;
      trs_enviar = trs.toArray();
      enviar_facturas()
      console.log("trs_seleccionados", trs.length )
    }
  }

  function events()
  { 
    $("#datatable").on('click', "tbody tr" , seleccionar_tr );
    $("#select_all").on('click',  toggleSelect   );
    $(".enviar-sunat").on('click', enviar_sunat );
    $("[name=tipo]").on('change' , function(){ table.draw() })
  }

function checkedDatos(data,type,row,meta)
{ 
  let valor = Number(data); // 1 - 0
  let icon = ['fa fa-square-o', 'fa fa-check-square-o']
  let button = "<a class='btn btn-default btn-xs'>" +
   "<span class='" + icon[valor] + "'> </span></a>";
  return button;  
};


  function init()
  {
    headerAjax()
    initDatable();
    events();
  };


  // Ejecutar todas las funciones
  init();

});