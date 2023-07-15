var accion; 

$(document).ready(function()
{
  function changePlantillas()
  {
    let tipo = $("[name=tidcodi] option:selected").attr('data-tipo');
      $(".row-tipo").each(function (index, dom) {
        $(this).attr('data-tipo') === tipo ?
          $(this).show() :
          $(this).hide();
      })
  }

  $("[name=tidcodi]").on('change' , function(){
    changePlantillas()
  })


  $("#usuario_documento").on('submit', function (e) {

    let data = {
      "empresa_id": $('[name=empresa_id]').val(),
      "usucodi": $('[name=usucodi] option:selected').val(),
      "tidcodi": $('[name=tidcodi] option:selected').val(),
      "loccodi": $('[name=loccodi] option:selected').val(),
      'sercodi': $('[name=sercodi]').val(),
      'numcodi': $('[name=numcodi]').val(),
      '_method': $('[name=_method]').val(),
      'defecto': Number($('[name=defecto]').is('checked')),
      'estado': Number($('[name=estado]').is('checked')),
      'contingencia': Number($('[name=contingencia]').is('checked')),
      // ----------------------------------------------------------------------
      'a4_plantilla_id': $(".row-tipo:visible").find(".formato_a4 option:selected").val(),
      'a5_plantilla_id': $(".row-tipo:visible").find(".formato_a5 option:selected").val(),
      'ticket_plantilla_id': $(".row-tipo:visible").find(".formato_ticket option:selected").val(),
      // ----------------------------------------------------------------------
      'impresion_directa' : $('[name=impresion_directa] option:selected').val(),
      'cantidad_copias' : $('[name=cantidad_copias] option:selected').val(),
      'nombre_impresora' : $('[name=nombre_impresora]').val(),
    }
    
    $("#load_screen").show();

    let url = $("#usuario_documento").attr('action');
      
    let funcs = {
      success: function (data) {
        notificaciones('Accion Exitosa','success');
        $("#load_screen").hide();
        setInterval(() => {
          location.href = $(".btn-back").attr('href');
        }, 1000);

      },      
      complete : function(data){
        $("#load_screen").hide();
      }
    };

    console.log( "data", data);

    ajaxs( data , url, funcs );


    // |||||| a-b-c-d-e-f-g-h-j-k-l-m-n-ñ-o-p-q-r-s-t-u-v-w-x-y-z ||||||
    console.log("usuario_documento")



    e.preventDefault();
    return false;
  })


  function showPDF(e)
  {
    $("#modalData").attr('data-backdrop', 'true')

    let data = {
      'plantilla_id': $(this).parents('.input-group').find('select option:selected').val(), 
    };
    let url = $(this).parents('.input-group').find('select option:selected').attr('data-route');
    let funcs = {
      'success' : function(response){
        
        //
        let $div = $("<embed>").attr({
          'src': response.route,
          'frameborder': '0',
          'width': '100%',
          'height': '850px'
        });


        $("#modalData .modal-dialog")
          .removeClass('modal-md')
          .addClass('modal-lg');
        $("#modalData .modal-header").empty();
        $("#modalData .modal-title").empty();
        $("#modalData .modal-body").empty();
        $("#modalData .modal-body").append($div);
        $("#modalData").modal();
      }
    };

    window.ajaxs(data, url, funcs );

    e.preventDefault();
    return false;
  }


  function testPrint(e)
  {
    let impresora_nombre = $("[name=nombre_impresora]").val().trim()

    if ( impresora_nombre == "" ){
      notificaciones('Para hacer la prueba de impresiòn, tiene que colocar el nombre de la impresora' , 'error');
      $("[name=nombre_impresora]").focus();
    }

    else {

      let url = $(this).attr('data-route');

      let data = {
          'impresora_nombre' : impresora_nombre
      }

      let funcs = {

        'success': function (response) {

          function successPrint(){
            console.log(response);
            notificaciones( 'Al parecer se ha realizado la impresiòn, por favor revisar que se halla imprimido correctamente' , 'success');
            console.log("successPrint")
          }

          function errorPrint(error) {
            console.log("errorPrint")
            notificaciones(error, 'error' );
          }

          let printer = new PrintTest(response.data, response.data.impresora_nombre, successPrint, errorPrint );
          printer.errorFunc = errorPrint;
          printer.print();
        }
      };

      window.ajaxs( data , url, funcs);
    
    }

    
    console.log("testPrint");

    e.preventDefault();
    return false;

  }

  function changePrintActivity()
  {

    let activateBtnPrintTest = Number($("[name=impresion_directa] option:selected").val());
    let $parentBtnPrintTest = $(".parent-test-print-btn");
    let $spanBtnPrintTest = $(".spanBtnBtnTest");
    
    if (activateBtnPrintTest){
      $parentBtnPrintTest.addClass('input-group')
      $spanBtnPrintTest.removeClass('hide')
    }
    else {
      $parentBtnPrintTest.removeClass('input-group')
      $spanBtnPrintTest.addClass('hide')
    }

  }


  const obtenerListaDeImpresoras = () => {
    ConectorPlugin.obtenerImpresoras()
      .then(listaDeImpresoras => {
        listaDeImpresoras.forEach(nombreImpresora => {
          console.log( "nombreImpresora", nombreImpresora );
          // const option = document.createElement('option');
          // option.value = option.text = nombreImpresora;
          // $listaDeImpresoras.appendChild(option);
        })
      })
      .catch(() => {
        notificaciones("Error obteniendo impresoras. Asegúrese de que el plugin se está ejecutando");
      });
  }

  obtenerListaDeImpresoras()
  $("[name=impresion_directa]").on('click', changePrintActivity );
  $(".print-test").on( 'click' , testPrint );
  $(".btn-show-pdf").on( 'click' , showPDF );

  changePlantillas();

})
