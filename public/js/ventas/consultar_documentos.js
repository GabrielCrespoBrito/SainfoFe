function get_data( con_items = false )
{
  let data = {
    tipo_documento : $("[name=tipo_documento] option:selected").val(),
    serie_documento : $("[name=serie_documento] option:selected").val(),
    fecha_desde : $("[name=fecha_desde]").val(),
    fecha_hasta : $("[name=fecha_hasta]").val(),
    correlativo_desde : $("[name=correlativo_desde]").val(),
    correlativo_hasta : $("[name=correlativo_hasta]").val(),
    reprocesar : $(".reprocesar_faltantes").find("input").is(':checked')
  };

  items = [];

  if( con_items ){

    $("#table_documentos tbody tr").each(function(index,dom){
      let tr = $(this);
      items.push({
        'VtaOper' : tr.find("td:eq(0)").text(),
        'Correlativo' : tr.find("td:eq(1)").text(),      
        'Fecha' : tr.find("td:eq(2)").text(),
        'Estado' : tr.find("td:eq(3)").text(),
      });

    })

    data.items = items;
  }

  return data;
}

function buscar_documentos(e)
{
  e.preventDefault();
  let input_desde = $("[name=correlativo_desde]");
  let input_hasta = $("[name=correlativo_hasta]");

  if( executing_ajax){
    console.log("Ejecutando ajaxs");
    return;
  }

  if(! input_desde.val().length ){
    noti_focus( input_desde , "Debe seleccionar el rango de correaltivos a buscar " , "error" );
    return;
  }

  if(! input_hasta.val().length ){
    noti_focus( input_hasta , "Debe seleccionar el rango de correaltivos a buscar " , "error" );
    return;
  }

  let data = get_data();

  desactivar_button(".buscar");
  $(".block_elemento").show();
  $(".resumen_consulta").hide();
  $("#table_documentos tbody").empty();  
  $(".reporte_pdf").hide();

  let funcs = {      
    success : agregar_elementos,
    complete : function(){ $(".block_elemento").hide(); activar_button( ".buscar" ) }
  };

  ajaxs( data , url_consultar , funcs );
}


function agregar_elementos(data)
{
  $(".resumen_consulta").show(1000);
  $(".resumen_consulta .procesadas .value").text( data.totales );
  $(".resumen_consulta .encontradas .value").text( data.encontradas );
  $(".resumen_consulta .faltantes .value").text( data.faltantes );
  $(".resumen_consulta .inexistente .value").text( data.inexistente );


  if( data.ventas_faltantes.length ){
    add_to_table( "#table_documentos" , data.ventas_faltantes , [
      { name : 'VtaOper' },
      { name : 'VtaNume' },
      { name : 'VtaFvta' },
      { name : 'Estado' , render : (value,index,data,tr) => {       

        console.log("arguments: value,index,data,tr",value,index,data,tr);

        if( value == "0" ){
          tr.addClass("inexistente");
        }

        let content = value ? "no se encuentra sunat" : "no se encuentra en sistema"
        return content;
      }},      
    ])

    $(".reporte_pdf").show();
  }
}


function descargar_reporte(data)
{
  console.log('descargar_data' , data );;
  let contenido = "data:application/pdf;base64," + data.contenido;  
  download( contenido , data.nombre, "application/pdf" );  
}

function generar_reporte()
{
  let data = get_data(true);

  let funcs = {      
    success : descargar_reporte,
  };

  ajaxs( data , url_generar_reporte , funcs );
}


function poner_series()
{
  let data = JSON.parse($( "option:selected" , this ).attr('data-series'));
  add_to_select( "[name=serie_documento]" , data , 'id' , 'nombre' );
}

function establecer_parametro()
{
  let value = $(this).find("input").is(':checked');

  console.log("value" , value );

}

function poner_rangos(data)
{
  $("[name=correlativo_desde]").val( data.desde );
  $("[name=correlativo_hasta]").val( data.hasta );
}

function ajaxs_search( tipo , previous_funcs = false )
{
  let datas = {
      'buscar_rangos' : {
        'fecha_desde' : $("[name=fecha_desde]").val(),
        'fecha_hasta' : $("[name=fecha_hasta]").val(),
        'tipo_documento' : $("[name=tipo_documento]").val(),
        'serie_documento' : $("[name=serie_documento]").val(),
      }
  }

  let urls = {
      'buscar_rangos' : url_buscar_rangos
  }

  let funcs =  {
    'buscar_rangos' : {
      'success' : poner_rangos,
    }
  }
  previous_funcs ? previous_funcs() : null;  
  ajaxs( datas[tipo]  , urls[tipo] , funcs[tipo] );
}

function events()
{ 
  $("[name=tipo_documento]").on('change' , poner_series );
  $(".buscar").on('click' , buscar_documentos );  
  $(".reporte_pdf").on('click' , generar_reporte );
  $(".reprocesar_faltantes").on('click' , establecer_parametro );
  $("[name=tipo_documento],[name=serie_documento],[name=fecha_desde],[name=fecha_hasta]").on('change' ,  
    ajaxs_search.bind( null , 'buscar_rangos' , () => {$("#table_documentos tbody").empty()} ) );
}


init( events , datepicker );
