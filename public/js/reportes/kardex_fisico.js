function select2_init()
{
  $('[name=articulo_desde],[name=articulo_hasta]').select2({
    placeholder: "Buscar producto",
    minimumInputLength: 2,
    ajax: {
      url: url_search_producto,
      dataType: 'json',
      data: function (params) {
        return {
          data: $.trim(params.term),
          id: 'true',
        };
      },
      processResults: function (data) {
        console.log( "data" , data );
        return {
          results: data
        };

      },
      cache: true
    }
  });
}

function setOtherArticle(e)
{
  let id = e.target.value;
  let text = $.trim(e.target.textContent);

  if(id){
    var newOption = new Option(text, id, false, false);
    $("[name=articulo_hasta]").append(newOption).trigger('change');
  }
}

function generateReport(e)
{
  // let data = {
  //   articulo_desde : $("[name=articulo_desde]").val(),
  //   articulo_hasta : $("[name=articulo_hasta]").val(),
  //   fecha_desde : $("[name=fecha_desde]").val(),
  //   fecha_hasta : $("[name=fecha_hasta]").val(),
  //   LocCodi : $("[name=LocCodi]").val(),
  //   articulo_movimiento : $("[name=articulo_movimiento]").val(),
  // }
   

  // if( !data.articulo_desde || !data.articulo_hasta ) {
  //   noti_focus( "[name=articulo_desde]" , "Es necesario los codigos de los productos");
  //   e.preventDefault();
  //   return false;
  // }
  
  // if( !data.fecha_desde || !data.fecha_hasta ) {
  //   noti_focus( "[name=articulo_desde]" , "Es necesario el rango de fecha");
  //   e.preventDefault();
  //   return false;
  // }
}

function events()
{

  const date = new Date;

  // $("[name=fecha_inicio]").val($("[name=fecha_inicio]").attr('data-date'))
  // $("[name=fecha_fin]").val($("[name=fecha_fin]").attr('data-date'))

  select2_init();
  $('[name=articulo_desde]').on('select2:close', setOtherArticle );
  $('.formReporte').on('submit', generateReport );
}


init(  
  datepicker,
  events
)



