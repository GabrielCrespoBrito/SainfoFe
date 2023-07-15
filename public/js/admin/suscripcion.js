console.log("suscripcion");


$(".active-update-input-fecha-suscripcion").on('click', function (e) {

  console.log("active");

  $(".active-update-input-fecha-suscripcion").show();
  $(".desactive-update-input-fecha-suscripcion").show();
  $(".update-fecha-suscripcion").show();

  let $input = $(".input-date-vencimiento")

  let fecha = $input.val().split(" ");


  $input
    .attr('type', 'date')
    .removeAttr('disabled')
    .val(fecha[0])

  e.preventDefault();
  return false;
})

$(".desactive-update-input-fecha-suscripcion").on('click', function (e) {

  console.log("desactive");

  $(".active-update-input-fecha-suscripcion").show();
  $(".desactive-update-input-fecha-suscripcion").hide();
  $(".update-fecha-suscripcion").hide();


  e.preventDefault();

  let $input = $(".input-date-vencimiento")
  
  $input
  .attr('type', 'text')
  .val( $input.attr('data-initial') )
  .attr('disabled', 'disabled');
  // $(".input-date-vencimiento").removeAttr('disabled');
})



$(".update-fecha-suscripcion").on('click' , function(e){

  e.preventDefault();

  // $(".input-date-vencimiento")

  let $input = $(".input-date-vencimiento");

  // input-date-vencimiento

  let data = {
    'fecha': $(".input-date-vencimiento").val(),
  }

  let url = $(this).attr('data-url');

  let funcs = {
    success : function(data){
      $input.attr('disabled', 'disabled');
      $input.attr('data-initial', data.fecha)
      $input.attr('type', 'text' )
      $input.val(data.fecha);

      $(".active-update-input-fecha-suscripcion").show();
      $(".desactive-update-input-fecha-suscripcion").hide();
      $(".update-fecha-suscripcion").hide();

      $(".cant-dias").text(data.dias);
      // $(".cant-duracion").text(data.duracion);

      notificaciones('Fecha Actualizada exitosamente', 'success');
    },
    complete: function () {
      
    },
  }
  ajaxs( data , url , funcs );
  return false;
})