function preparar_reporte(e)
{
  let url = $(this).data('href');
  let tipo = $("[name=tipo_reporte]:checked").val();
  let cliente = $("[name=cliente]").val();
  let local = $("[name=local]").val();
  let fecha_desde = $("[name=fecha_desde]").val();
  let fecha_hasta = $("[name=fecha_hasta]").val();
  let tipo_documento = $("[name=tipo_documento] option:selected").val();
  let serie = $("[name=serie]").val();
  let vendedor = $("[name=vendedor]").val();
  let formato = $("[name=formato]").val();
  let estado_sunat = $("[name=estado_sunat]").val();
  cliente = cliente == null ? 'todos': cliente;
  url = url
  .replace('tipo' , tipo)
  .replace('cliente' , cliente)
  .replace('local__' , local)
  .replace('fecha_desde' , fecha_desde)
  .replace('fecha_hasta', fecha_hasta)
  .replace('vendedor', vendedor)
  .replace('serie' , serie )

  if( is_venta == 1){
    url = 
    url
    .replace('tipo_documento' , tipo_documento)
  }
  else {
    url = 
    url
    .replace('tipo_documento' , estado_sunat)
  }

  window.open(
    url, 
    "_blank",
    "toolbar=yes,"    +
    "scrollbars=yes," +
    "resizable=yes,"  +
    "top=500,"        +
    "left=500,"       +
    "width=400,"      +
    "height=400"
  );
}


function select2Init() {

  console.log("ejecutando select2Init");

  const url_cliente = $('#cliente').attr('data-url');
  let selectClient = $('#cliente');
  selectClient.select2({
    placeholder: selectClient.data('placeholder'),
    minimumInputLength: 2,
    ajax: {
      url: url_cliente,
      dataType: 'json',
      data: function (par) {
        return {
          data: $.trim(par.term),
          type: selectClient.data('type')
        };
      },
      processResults: function (data) {
        return { results: data };
      },
      cache: true
    }
  });
}

// events
function events()
{
  $(".buscar").on('click' , preparar_reporte )  
  select2Init();
}

events();
datepicker()