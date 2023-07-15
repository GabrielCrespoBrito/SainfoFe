function successAdicionalFunc()
{
  location.reload();
}

$(function(){
  $(".change-status").on('click' , function(e){
    e.preventDefault();
    if(confirm("Esta seguro se cambiar el estatus del usuario?")){
      location.href = $(this).attr("href");
    }
  });
  return false;
})