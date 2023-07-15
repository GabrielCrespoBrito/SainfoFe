function changeAllPrecios()
{
  $(".generic_content").each(function(){
    changePrecios(this);
  }); 

}

function changePrecios( generic_content ) {

  let $generic_content = $(generic_content);
  let noEsElegible = $generic_content.is('.no-elegible');
  let $selectDuracion = $(".plan-duracion", generic_content );
  let $optionDuracion = $selectDuracion.find("option:selected", generic_content );
  let info = $optionDuracion.data('info');
  let $price = $(".price", generic_content);
  let $linkConfirm = $(".link-confirm", generic_content);
  
  let $descuento = $(".ribbon", generic_content);
  let total = String(info.total).split(".");
  let descuentoValue = Number(info.descuento_porc);

  let entero = Number(total[0]);
  let decimal = total.length > 1 ? Number(total[1]).toFixed(2) : '00';

  if(descuentoValue){
    $descuento
    .show()
    .find('.descuento').text( descuentoValue + "%" );
  }

  else {
    $descuento.hide();
  }

  // console.log(noEsElegible)

  let link = noEsElegible ? '#' : $linkConfirm.data('href').replace('@@', $selectDuracion.val() );

  if( noEsElegible ){
    $linkConfirm.prop('disabled', true)
  }

  console.log(link);
  $linkConfirm.attr('href',link);

  $price.find('.currency').text(entero);
  $price.find('.cent').text("." + decimal);
}


$(function(){

  // Poner inicialmente los precios correctos
  changeAllPrecios();

  // Al cambiar de duración poner el precio pertinente
  $(".plan-duracion").on('change' , function(){
    changePrecios($(this).parents('.generic_content')[0]);
  });

})