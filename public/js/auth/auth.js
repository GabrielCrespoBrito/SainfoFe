
function defaultErrorMessageFunc(data)
{
  console.log( "error" , data );
  let errors = data.responseJSON.errors;
  let mensaje = data.responseJSON.message;
  let erros_arr = [];
  for( prop in errors ){
    for( let i = 0; i < errors[prop].length; i++  ){
      erros_arr.push( errors[prop][i] );
    }
  }
  notificaciones( erros_arr , 'error' , mensaje )
}


// notificaciones
function notificaciones ( mensaje , type = 'info' , heading = '' ){
  var info = {
    'heading'   : heading,
    'position'  : 'top-center',
    'hideAfter' : false, 
    'showHideTransition' : 'fade' 
  };

  $.toast({
    heading   : info.heading,
    text      : mensaje,
    position  : info.position,
    showHideTransition : info.showHideTransition, 
    hideAfter : info.hideAfter,
    escapeHtml : true,
    icon      : type,
    stack     : false
  });
};

window.notificaciones = notificaciones;

// Devuelve un booleano si es un RUC válido
// (deben ser 11 dígitos sin otro caracter en el medio)
function rucValido(valor) 
{
  // Codigo correcto
  function esnumero(campo) { return (!(isNaN(campo))); }
  
  if (esnumero(valor)) {

    if (valor.length == 8) {
      suma = 0
      for (i = 0; i < valor.length - 1; i++) {
        digito = valor.charAt(i) - '0';
        if (i == 0) suma += (digito * 2)
        else suma += (digito * (valor.length - i))
      }
      resto = suma % 11;
      if (resto == 1) resto = 11;
      if (resto + (valor.charAt(valor.length - 1) - '0') == 11) {
        return true
      }
    } 
    else if (valor.length == 11) {
      suma = 0
      x = 6
      for (i = 0; i < valor.length - 1; i++) {
        if (i == 4) x = 8
        digito = valor.charAt(i) - '0';
        x--
        if (i == 0) suma += (digito * x)
        else suma += (digito * x)
      }
      resto = suma % 11;
      resto = 11 - resto

      if (resto >= 10) resto = resto - 10;
      if (resto == valor.charAt(valor.length - 1) - '0') {
        return true
      }
    }
  }
  return false
}

$(function(){

  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  
  $("#cliente_form, #erp_form").on('submit' , function(e){
    $(".enviar" ).prop('disabled',true);
  });

  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' /* optional */
  });

  $("#form-sol").on('submit' , function(e){

    e.preventDefault();
    
    let url = $(this).attr('action');
    let $inputRuc = $("[name=ruc]", this )
    let $inputUsuarioSol = $("[name=usuario_sol]", this )
    let $inputClaveSol = $("[name=clave_sol]", this)
    let ruc = $inputRuc.val();
    let usuario_sol = $inputUsuarioSol.val();
    let clave_sol = $inputClaveSol.val()

    if( !rucValido(ruc)  ){
      notificaciones("Por favor introduzca un ruc valido", 'error','Ruc invalido');
      $inputRuc.focus();
      return false;
    }

    let data = {
      ruc : ruc,
      usuario_sol : usuario_sol,
      clave_sol : clave_sol
    } 

    // console.log( url, data )
    $(".enviar").addClass('disabled');
    $inputRuc.prop('disabled', true)
    $inputUsuarioSol.prop('disabled', true)
    $inputClaveSol.prop('disabled', true)


    $.ajax({
      url : url,
      data: data,
      success : function(data){
        console.log("success", data);
        location.reload();
      },
      error : function(data){
        defaultErrorMessageFunc(data);
        $(".enviar").removeClass('disabled');
        $("#form-sol input").prop('disabled', false);
        $inputRuc.prop('disabled', false)
        $inputUsuarioSol.prop('disabled', false)
        $inputClaveSol.prop('disabled', false)
        $(".enviar").removeClass('disabled');
      },
      complete : function(data){
      },
      type : 'post'
    })

    return false;
  })


  /**
   * Formulario de envio de email recuperación de contraseña y cambiao de contraseña 
  */
 $("#form-send").on('submit', function (e) {
  $(".btn-send").prop('disabled', true);
  $(".btn-send").find('.estado-cargando').show();
  $(".btn-send").find('.estado-default').hide();
})
  

  $("#form-register").on('submit', function (e) {
  
    $("#form-register .enviar").prop('disabled' , true );
  
  })


  // Envio

  $("#form-empresa").on('submit', function (e) {

    e.preventDefault();

    let url = $(this).attr('action');
    let data = $(this).serialize();

    // console.log( url, data )
    $(".enviar").prop('disabled', true);
    $(".enviar").find('.estado-cargando').show();
    $(".enviar").find('.estado-default').hide();

    $.ajax({
      url: url,
      data: data,
      success: function (data) {
        console.log("success", data);
        location.href = data.route;
        return;
      },
      error: function (data) {
        defaultErrorMessageFunc(data);
        $(".enviar").prop('disabled', false);
        $(".enviar").find('.estado-cargando').hide();
        $(".enviar").find('.estado-default').show();        

      },
      complete: function (data) {
        $(".enviar").prop('disabled', false);

      },
      type: 'post'
    })

    return false;
  })



  

  $('.send-code-seguridad').on('click' , function(e){

    let exprRegular = /\d{4}/;

    e.preventDefault();
    let $codeNumberInput = $('[name=code_seguridad]');
    let code = $.trim($codeNumberInput.val());    
    console.log("code" , code)
    
    if( code == "" ){
      notificaciones('Introduzca el código de verificación, por favor', 'error');
      $codeNumberInput.focus();
      return;
    }

    if( !code.match(exprRegular) ){
      notificaciones('El codigo tiene que tener 4 digitos', 'error');
      $codeNumberInput.focus();
      return;
    }

    let url = $(this).data('url');
    $codeNumberInput.prop('disabled', true);
    $('.send-code-seguridad').addClass('disabled');

    $.ajax({
      url : url,
      data: {  
        code : code
      }, 
      success : function(data){
        console.log( "success", data );
        location.href = data.route;
      },
      error : function(data){
        defaultErrorMessageFunc(data),
        notificaciones( erros_arr , 'error' , mensaje )
        $('.send-code-seguridad').removeClass('disabled');
        $codeNumberInput.prop( 'disabled' , false );
      },
      complete : function(data){
      },
      type : 'post'
    })

  });

  $('.save-number').on('click' , function(e){

    let exprRegular = /\d{9}/;
    
    e.preventDefault();
    let $inputPhoneNumber = $('[name=guardar]');
    let phoneNumber = $.trim($inputPhoneNumber.val());

    if(phoneNumber == ""){
      notificaciones('Introduzca su número de telefono', 'error');
      $inputPhoneNumber.focus();
      return;
    }

    if(! phoneNumber.match(exprRegular)){
      notificaciones('El su número tiene que tener 9 digitos', 'error');
      $inputPhoneNumber.focus();
      return;
    }
    
    $('.save-number').prop('disabled' , true);
    $inputPhoneNumber.prop('disabled' , true);

    let url = $("#form-verificar").attr('action');

    $.ajax({
      url : url,
      data: {  
        phone : phoneNumber
      }, 
      success : function(data){
        location.reload();
      },
      error : function(data){
        defaultErrorMessageFunc(data);
        $('.save-number').prop('disabled', false);
        $inputPhoneNumber.prop('disabled', false);
      },
      complete : function(data){
      },
      type : 'post'
    })

  });




  //
  $("#form-empresa .ruc-consulta").on('click' , function(e)
  {

    e.preventDefault();
    
    let $rucSearchBtn = $(this);
    let url = $(this).attr('data-route');
    let $inputRuc = $("#form-empresa [name=ruc]");
    let $razonSocial = $("#form-empresa [name=razon_social]");
    let $nombreComercial = $("#form-empresa [name=nombre_comercial]");
    let $direccion = $("#form-empresa [name=direccion]");
    let $email = $("#form-empresa [name=email]");

    let ruc = $.trim($inputRuc.val());
    
    if( !rucValido(ruc)  ){
      notificaciones("Por favor introduzca un ruc valido", 'error','Ruc invalido');
      $inputRuc.focus();
      return false;
    }

    let data = {
      numero : ruc
    } 
    
    $razonSocial.val('');
    $nombreComercial.val('');
    $direccion.val('');
    $email.val('');
    
    $rucSearchBtn.prop('disabled', true);
    $inputRuc.prop('disabled', true)

    $.ajax({
      url : url,
      data: data,
      success : function(data){
        let razon_social = data.data.razon_social;
        let caracter_inicial = razon_social.charAt(0);
        razon_social = caracter_inicial == '"' || caracter_inicial == "'" ? razon_social.slice(1) : razon_social;
        let caracter_final = razon_social.charAt( razon_social.length - 1 );
        razon_social = caracter_final == '"' || caracter_final == "'" ? razon_social.slice( 0 , -1 ) : razon_social;

        // console.log("success", data);
        $razonSocial.val( razon_social )
        $direccion.val( data.data.direccion )
        $nombreComercial.val( razon_social );
        $email.val( data.data.email )
      },
      error : function(data){
        defaultErrorMessageFunc(data);
        $(".enviar").removeClass('disabled');
        $("#form-sol input").prop('disabled', false );
        $inputRuc.prop('disabled', false )
        // $inputUsuarioSol.prop('disabled', false)
        // $inputClaveSol.prop('disabled', false)
        $(".enviar").removeClass('disabled');
      },
      complete : function(data){
        $rucSearchBtn.prop('disabled', false);
        $inputRuc.prop('disabled', false)
      },
      type : 'post'
    })

    return false;
  })


  
  // 


});