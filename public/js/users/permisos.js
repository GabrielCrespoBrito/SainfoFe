$(function(){

  console.log("permisos");

  $(".seleccion").on('click', function(e){
    
    e.preventDefault();
    let $checkBoxs = $(this).parents('form').find('input[type=checkbox]');
    let isSeleccionInicial = $(this).is(".seleccion-inicial");
    let selectUnselect = $(this).attr('data-action') == "1";

    $checkBoxs.each(function(index,dom){
      
      if( isSeleccionInicial ){
        checkedInput = $(dom).attr('data-default') == "1";
      }

      else {
        checkedInput = selectUnselect;
      }

      $(dom).prop('checked', checkedInput);

    })
  });

})