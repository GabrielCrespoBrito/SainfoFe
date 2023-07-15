console.log( 'adminPanel' )
// alert("");
$(".label-date-all").on('click', function(e){

  e.preventDefault();

  $("#load_screen").show();

  let funcs = {
    complete : function(data){
      $("#load_screen").hide();
      location.reload();
    }
  }

  ajaxs( {}, $(this).attr('href') , funcs );
})


window.consultLocals = function(show_load_screen = false)
{
  if( ! $("[name=empresa_id]").length ){
    return;
  }

  if (show_load_screen) {
    $("#load_screen").show()
  }

  let url = $("#local").attr('data-route');
  let funcs = {
    success: function (locales) {

      // Limpiar opciones
      $("#local").empty();

      // Poner Local por defecto al array de locales
      locales.unshift({ id: '', name: '-- Todos --', direccion: null });

      console.log("locales", locales);

      for (let i = 0; i < locales.length; i++) {
        let local = locales[i];
        let $optionLocal = $(`<option value="${local.id}"> ${local.name} - ${local.direccion ? local.direccion : ''} </option>`);
        $("#local").append($optionLocal);
      }
    },
    complete: function (data) {
      $("#load_screen").hide()
    }
  };

  let data = {
    'empresa_id': $("[name=empresa_id]").val()
  };

  ajaxs(data, url, funcs);
}