function ini_table()
{

  function agregar_decimales(value){
    return fixedNumber(value)
  }

  function checkedDatos(dat,type,row,meta)
  { 
    // return;
    let valor = Number(dat); // 1 - 0
    let icon = ['fa fa-square-o', 'fa fa-check-square-o']
    let info = {
      7 : {
        'clase' : 'docu accion_xml',
      },
      8 : {
        'clase' : 'docu accion_pdf',      
      },
      9 : {
        'clase' : 'docu accion_cdr',      
      },
      10 : {
        'clase' : 'accion_descargar',      
      },
    };


    if( meta.col == 10 ){      
      return "<input type='button' value='Descargar' class='btn btn-xs descargar_files btn-flat btn-default'>";
    }
    else {
      console.log("valor",valor);
      if( valor === 0 ){        
        return "-";
      }
      console.log("llegue aqui" , valor )
      return "<input type='checkbox' class='" + info[meta.col].clase +  "'>";     
    }

  };

  table_documentos = $('#datatable').DataTable({
    "processing" : true,
    "serverSide" : true,
    "lengthChange": false,
    "ordering": false,      
    "ajax": url_route_actividad,
    "oLanguage": {"sSearch": "", "sLengthMenu": "_MENU_" },
    "initComplete" : function initComplete(settings, json){         
      $('div.dataTables_filter input').attr('placeholder', 'Buscar');
    },
    "columns" : [
      { data : 'PCNomb'  },
      { data : 'Fecha' },       
      { data : 'Nombre' },       
      { data : 'Descripcion' },       
      { data : 'Fecha' },       
      
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


function check_elements_download()
{
  $("tr").css('outline','none');

  let data = null;

  if( data = documentos_ready(this)){

    
    let funcs = {
      success : download_file    
    };  

    ajaxs( data , url_descargar_files, funcs );
  }

  else {
    let tr_parent = $(this).parents('tr');
    error_choose_document(tr_parent)    
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


function events()
{
  $("#datatable").on('click' , '.descargar_files' , check_elements_download );
  $("#datatable").on('click' , '.enviar_mail' , check_elements_email );
}


init(
	ini_table,
  events
)

