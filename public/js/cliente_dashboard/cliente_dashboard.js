function ini_table()
{

  function agregar_decimales(value){
    return fixedNumber(value)
  }

  console.log("registradno datatable");

  window["table"] = $('#datatable').DataTable({
    "processing" : true,
    "serverSide" : true,
    "bFilter": false,
    "lengthChange": false,
    "ordering": false,      
    "ajax": {
      url : url_route_clientes_consulta,
      data : function(d){
        return $.extend( {}, d, {
          "filter" : $("[name=filter]").val(),        
          "fecha_desde": $("[name=fecha_desde]").val(),
          "fecha_hasta": $("[name=fecha_hasta]").val(),
          "tipo"       : $("[name=tipo] option:selected").val(),
          "estado"       : $("[name=estado] option:selected").val(),          
        });
      }
    },
    "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
    "initComplete" : function initComplete(settings, json){         
      $('div.dataTables_filter input').attr('placeholder', 'Buscar');
    },
    "columns" : [
      { data : 'VtaOper' },
      { data : 'VtaFvta' },       
      { data : 'TidCodi' },       
      { data : 'VtaSeri' },       
      { data : 'VtaNumee'},       
      { data : 'moneda.monnomb' },             
      { data : 'VtaImpo' , render : agregar_decimales },
      { data : 'estado'  },
      { data : 'fe_obse'  },
    ]
  });	
}


function download_file(data)
{
  let contenido = "data:application/zip;base64," + data.contenido;  
  download( contenido , data.nombre, "application/zip" );  
}


function documentos_ready( boton )
{
  let tr_parent = $(boton).parents('tr'),
    xml = $( '.accion_xml' , tr_parent ).prop('checked'),
    pdf = $( '.accion_pdf' , tr_parent ).prop('checked'),
    cdr = $( '.accion_cdr' , tr_parent ).prop('checked');

  if( xml || cdr || pdf ){
    return {
      id_factura : tr_parent.find('td:eq(0)').text(),
      xml : xml,
      pdf : pdf,
      cdr : cdr,    
    };
  }

  else {
    return false;
  }
}

function check_elements_download(e)
{

  e.preventDefault();

  if( $("tr.select").length ){
    
    let ids = [];

    $(".block_elemento").show();

    $("tr.select").each(function(){
      ids.push( $(this).find("td:eq(0)").text() )
    })

    let data = {
      'ids' : ids
    };

    let funcs = { success : download_file , complete : function(){
      $(".block_elemento").hide();
    } };  

    ajaxs( data , url_descargar_files, funcs );
  }

  else {    
    notificaciones("Tiene que seleccionar los documentos a descargar" , "error");
  }


}


function email_enviado(data)
{
  console.log("email enviado", data );
}

function check_elements_email()
{
  $("tr").css('outline','none');

  let data = null;

  if( data = documentos_ready(this)){


    let funcs = {
      success : email_enviado    
    };  

    ajaxs( data , url_enviar_email_cliente, funcs );
  }

  else {
    let tr_parent = $(this).parents('tr');
    error_choose_document(tr_parent)
  }

}

function error_choose_document(tr)
{
  notificaciones("Tiene que elegir al menos un documento", "error", "Elija un documento");
  tr.css('outline','1px solid red');
}


Helper__.events.add(function(){

  $(".descargar_files").on('click' , check_elements_download );
  $("#datatable").on('click' , '.enviar_mail' , check_elements_email );
  $("#datatable").on('click' , '.enviar_mail' , check_elements_email );
  
  $(".search_action").on('click' , function(){
    $("[name=filter]").val(1);
    console.log(window.table);
    window.table.draw();

  })  

});

Helper__.init(ini_table);