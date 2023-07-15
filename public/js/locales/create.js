
$(document).ready(function (e) {

  initSelect2();

  $('[type=checkbox]').on('change', function(e){

    let className = $(this).is(':checked') ? 'c-green' : 'c-gray';

    $(this).parents('label')
    .removeClass()
    .addClass(className);
  })

  
  $('.change-serie').on('click', function (e) {

    e.preventDefault();
    
    let $this = $(this);
    let $inputSerie = $("#input-serie");
    
    if( $inputSerie.is('[readonly]') ){
      return;
    }

    if ($inputSerie.val() === $inputSerie.attr('data-initial') ){
      $this.parents('.form-group').removeClass('has-error')
      return;
    }

    let nuevaSerie = $inputSerie.val().trim().replace(' ', '');

    if (nuevaSerie.length != 3 || /^[a-zA-Z0-9]+$/.test(nuevaSerie) == false ){
      notificaciones( 'La serie tiene que 3 caracteres alpha numericos sin acentos' , 'error' );
      e.stopImmediatePropagation();
      return;
    }

    $this.parents('.form-group').removeClass('has-error');

    // Efecto de recarga
    $("#table-series").fadeOut(1000, function(){
      $(".serie-info").each(function (index, dom) {
        let firstLetter = $(dom).data('info').first_letter;
        $(".serie-nombre", dom).text(firstLetter + nuevaSerie.toUpperCase());
      })
      $(this).fadeIn(1000);
    });
    
    $inputSerie.attr('data-initial', nuevaSerie );
    

  })

  
  // $('[name=lista_id]').on('change', function (e) {
    // $("[name=lista_nombre]").val(  $('option:selected', this).attr('data-nombre'));
  // });



  $( '.change-input-readonly' ).on('click', function (e) {

    console.log("change-readonly");

    e.preventDefault();
    let inputId = $(this).attr( 'data-target' );
    let $input = $( '#' + inputId );
    let $icon = $(this).find('.fa');

    let inputIfReadonly = $input.is('[readonly]')

    // 
    if(inputIfReadonly){
      $input.removeAttr('readonly');
      $icon.removeClass('fa-pencil');
      $icon.addClass('fa-save');
    } 
    else {
      $input.attr('readonly', 'readonly');
      $icon.removeClass('fa-save');
      $icon.addClass('fa-pencil');
    }

  })

  $('.form-locales').on('submit', function (e) {
    
    // ------|------|------|------|------|------|------|------|

    let $inputSerie = $("#input-serie");

    if( !$inputSerie.is('[readonly]') ){
      // -------------------
      $(".change-serie").parents('.form-group').addClass('has-error')
      notificaciones('Tiene que guardar el nuevo numero de serie', 'error');
      return false;;
    }


    e.preventDefault();
    let data = $(this).serialize();
        $("#load_screen").show();
    let funcs = {
      success : function(data){
        notificaciones(data.message,'success');
        setTimeout(() => {
          location.href = $(".link-index").attr('href');
        }, 500);
        return;

      },
      complete : function(data){
        $("#load_screen").hide();
      } 
    }

    ajaxs(
      data,
      $(this).attr('action'), 
      funcs
    )

    return false;
  })
})