console.log("confirm");

$(function(){

  // Al cambiar de duración poner el precio pertinente
  $("#confirm-orden").on('click' , function(e){

    $("#confirm-orden").hide();
    $("#confirm-orden-load").show();
  });

})