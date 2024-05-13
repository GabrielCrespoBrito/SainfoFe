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
      'cantidad_copias' : $('[name=cantidad_copias]').val(),
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

    ajaxs( data , url, funcs );
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

          // 
var data = {
    "operaciones": [
        {
            "nombre": "Iniciar",
            "argumentos": []
        },
        {
            "nombre": "EstablecerAlineacion",
            "argumentos": [
                1
            ]
        },
        {
            "nombre": "EstablecerTamañoFuente",
            "argumentos": [
                1,
                1
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                true
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "GEFRANKA\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                false
            ]
        },
        {
            "nombre": "EstablecerTamañoFuente",
            "argumentos": [
                1,
                1
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Av. Los Faisnes N° 109 - 111 Urb. La Campiña - Chorrillos - Lima -Lima\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "TELF. 251-3639 / 252-0373  Cel. 994 092 470 / 981 535 210\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                true
            ]
        },
        {
            "nombre": "EstablecerTamañoFuente",
            "argumentos": [
                1,
                1
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "FACTURA ELECTRÓNICA\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "FF11-000150\n"
            ]
        },
        {
            "nombre": "EstablecerTamañoFuente",
            "argumentos": [
                1,
                1
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                false
            ]
        },
        {
            "nombre": "EstablecerAlineacion",
            "argumentos": [
                0
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Razon Social: CORPORACION SAINFO E.I.R.L. - SAINFO E.I.R.L.\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "RUC 20604067899\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Direccion: CAL. 9 LT. 31 MZ. Y A.H. 1 DE JUNIO - LIMA LIMA SAN JUAN DE MIRAFLORES\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Fecha:                2024-05-10               \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Vendedor:             OFICINA                  \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Forma de Pago:        CONTADO                  \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                true
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Unid   Descripcion                             \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Cant.          P.Unit.                  Importe\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                false
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "NIU    ANGULO 3/4\" X 2.0 X 6MTS                \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "1              136.360                  136.360\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "NIU    ANGULO 3/4\" X 2.5 X 6MTS                \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "66             118.000                 7788.000\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "NIU    CAMISETA DE DREAY                       \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "6              25.000                   150.000\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "NIU    TELA                                    \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "2              6.000                     12.000\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "NIU    TANQUE TOROHIDAL ATIKA                  \n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "6              140.000                  840.000\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                true
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Son: OCHO MIL NOVECIENTOS VEINTISÉIS CON 36/100 SOLES\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                false
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                true
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "OP. GRAVADAS.:           S./             7564.7\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "TOTAL.:                  S./             8926.4\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                false
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                true
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "CUENTAS\n"
            ]
        },
        {
            "nombre": "EstablecerEnfatizado",
            "argumentos": [
                false
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "BCP S/.11111\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "-----------------------------------------------\n"
            ]
        },
        {
            "nombre": "EscribirTexto",
            "argumentos": [
                "Hora: 15:11:58\n"
            ]
        },
        {
            "nombre": "Corte",
            "argumentos": [
                1
            ]
        }
    ],
    "nombreImpresora": "POS",
    "serial": ""
};
          //

          let printer = new PrintTest(data, response.data.impresora_nombre, successPrint, errorPrint );
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
    ConectorPluginV3.obtenerImpresoras()
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
