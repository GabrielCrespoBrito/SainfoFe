
$(function(){

  $(".show-password").on('click' , function(e){
    console.log("click");
    let $input = $(this).parents('.input-group').find('input');
    let newType = $input.is('[type=password]') ? 'text' : 'password';
    let newIcon = newType == 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
    
    $input.attr('type' , newType );
    $(this).find('i').attr('class' , newIcon );

  });

  $("#form-cert").on('submit', function(e){
  
    let $form = $(this);
    let $button = $form.find("button");
    let url = $form.attr('action');
    var formData = new FormData(this);

    $button.prop('disabled', true);

    $.ajax({
      type: 'post',
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      success: (data) => {
        notificaciones( "Configuración exitosa, ya puede empezara a facturar electronicamente", "success");
        location.reload();
        console.log("success", data);
      } ,
      error: function(data){
        defaultErrorAjaxFunc(data),
      $(".block_elemento").hide();
      },
      complete: function (data) {
        console.log("complete" , data );
        $button.prop('disabled', false);
      }
    });

    e.preventDefault();
    return false;
  });



})