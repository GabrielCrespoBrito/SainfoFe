
function preparar_reporte(e)
{

  let fecha_desde = $("[name=fecha_desde]").val();
  let fecha_hasta = $("[name=fecha_hasta]").val();
  let local = $("[name=local]").val();
  let url = url_reporte;
  
  // return;
  
  url = url
  .replace( 'fecha_desde' , fecha_desde)
  .replace( 'fecha_hasta' , fecha_hasta)
  .replace( 'LocCodi' , local)

  console.log( "url" , url_reporte, url );
  
  $(this).attr('href', url)

  e.preventDefault();
}

// events
function events()
{
  $(".buscar").on('click' , preparar_reporte )  
}

events();
datepicker();


