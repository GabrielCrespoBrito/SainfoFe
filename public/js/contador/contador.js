function ini_table()
{

  function agregar_decimales(value){
    return fixedNumber(value)
  }

  table = $('#datatable').DataTable({
    "pageLength": 10,
    "responsive" : true,
    "processing" : true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_" ,
      "sEmptyTable": "No se encuentra documento"
    },
    "initComplete" : function initComplete(settings, json){         
      $('div.dataTables_filter input').attr('placeholder', 'Buscar Documento');
    },
    "serverSide" : true,
    "order": [[ 0, "desc" ]],
    "ajax": { 
      "url" : url_venta_consulta,  
      "data": function ( d ) {
       return $.extend( {}, d, {
         "buscar_por_fecha": 1,
         "fecha_desde": $("[name=fecha_desde]").val(),
         "fecha_hasta": $("[name=fecha_hasta]").val(),
         "tipo"       : $("[name=tipo] option:selected").val(),
         "estado"       : $("[name=estado] option:selected").val(),

         
       });
      }
    },
    "columns" : [      
      { data : 'nro_venta' },
      { data : 'TidCodi' , searchable : false },
      { data : 'VtaNume' , render : function(data,type,row,meta){
        return row.VtaSeri + "-" + row.VtaNumee;
      }},
      { data : 'VtaFvta' , searchable : false },         
      { data : 'PCNomb' , render : function(data){ return data.slice(0,15).concat("...") }},  
      { data : 'monabre' , searchable: false },
      { data : 'estado'  , searchable: false , className: 'estado' }, 
    ]
  });
}

function download_file(data)
{
  let mime = data.type == "zip" ? 'application/zip' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
  let contenido = 'data:' + mime + ";base64," + data.contenido;

  // console.log( "mime" , mime );
  download( contenido , data.nombre, mime );
}

function check_elements_download(e)
{

  e.preventDefault();

  let ids = [];
  let filter = true;
  let type = $(this).data('type');

  $(".block_elemento").show();

  if( $("tr.select").length) {
    filter = false;

    $("tr.select").each(function(){
      ids.push( $(this).find("td:eq(0)").text() )
    })
  }

  let data = {
    'ids' : ids,
    'filter' : filter,
    "fecha_desde": $("[name=fecha_desde]").val(),
    "fecha_hasta": $("[name=fecha_hasta]").val(),
    "estado": $("[name=estado]").val(),    
    "tipo" : $("[name=tipo] option:selected").val(),
    'type' : type
  }; 

  let funcs = { success : download_file , error : function(data){
    $(".block_elemento").hide();
    console.log( "data error" ,  data );
    notificaciones(data.responseJSON.message, "error");
  },  complete : function(){
    $(".block_elemento").hide();
  }};  

  ajaxs( data , url_descargar_files, funcs );
}

function error_choose_document(tr)
{
  notificaciones("Tiene que elegir al menos un documento", "error", "Elija un documento");
  tr.css('outline','1px solid red');
}

Helper__.events.add(function(){

  $(".descargar_files").on('click' , check_elements_download );
  
  $(".search_action").on('click' , function(){
    $("[name=filter]").val(1);
    window.table.draw();
  })

});

Helper__.init(ini_table);