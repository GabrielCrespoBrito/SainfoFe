// Obtener locales del usuario
window.get_locales_usuario = function ( select_selector, appendTo = true ) {
  
}

$(function(){

  // ------- Tipo de cambio -------

  // Copiar tipo de cambio
  $("#modalData").on('click', '#copy-tc' , function(){
    let valor_compra = $("#modalData").find("[data-type=compra]").text().trim();
    let valor_venta = $("#modalData").find("[data-type=venta]").text().trim();
    $("#modalData").find(".input-venta").val(valor_venta);
    $("#modalData").find(".input-compra").val(valor_compra);
  })

  // Cambiar campo input[type=password] para mostrar
  $(".show-hide-password").on( 'click', function (e) {

    e.preventDefault();
    let $input = $(this).parents('.input-group').find('input[type=password],input[type=text]');
    if( ! $input.length ){
      return;
    }
    
    let $icon = $(this).find('.fa');
    let isPassword = $input.is("[type=password]");
    let newClassName = isPassword ? 'fa fa-eye-slash' : 'fa fa-eye';
    let newType = isPassword ? 'text' : 'password';
    $input.attr( 'type' , newType );
    $icon.removeClass( 'fa-eye fa-eye-slash' );
    $icon.addClass( newClassName );




  });


  // Mostrar modal de tc
  $("#tc-modal").on('click' , function(e){
    e.preventDefault();
    let url = $(this).data('url');
    $.ajax({
      url: url, 
      type: 'post',
      success: (html) => {
        let $modalData = $("#modalData");
        $modalData.find('.modal-title').text('Tipo de cambio actual');
        $modalData.find('.modal-body').html(html);
        $modalData.modal();
      },
    });
    return false;
  });

  // Actualzar tc
  $("#modalData").on('submit', '#formTC', function (e) {
    e.preventDefault();

    $("#load_screen").show();
    let $modalData = $("#modalData");
    let data = $(this).serialize();
    let url = $(this).attr('action');

    $.ajax({
      url: url,
      data : data,
      type: 'post',
      success: (data) => {
        $("#load_screen").hide();
        $modalData.modal('hide');
        $.toast({
          heading: "Acción exitosa",
          text: data.message,
          position: 'top-center',
          // showHideTransition: '{{ session('N_showHideTransition') }}',
          hideAfter: false,
          icon: 'success',
        });
      },
      
    });

    return false;
  }) 


  // Poner locales por defecto
  function setDefaultLocals()
  {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    let url = $(".informacion_empresa-local").attr('data-consult');

    let funcs = {
      'success' : (response) => {

        for (let index = 0; index < response.locals.length; index++) {
          const local  = response.locals[index];
          let $option = `<option ${local.selected ? 'selected' : ''} data-id='${local.id}' value='${local.url}'>${local.descripcion}</option>`;
          $(".informacion_empresa-local").append($option);          
        }
      }
    };

    ajaxs({}, url, funcs ); 
  }

  $(".informacion_empresa-local").on('change' , function(e){
    if( confirm('Esta seguro que desea cambiar el local en el que esta trabajando?') ){
      location.href = $(".informacion_empresa-local option:selected").val();
    }
  })

  setDefaultLocals();

});