
console.log("hola mundo");


function habilitarDesabilidadBtnAction()
{
  let enabled = $("table .mark-input:checked ").length;
  let $btns = $(".action-massive");

  if( enabled ){
    $btns.removeClass('disabled');
  }
  else {
    $btns.addClass('disabled');
  }
}



$("table").on('change', '.mark-input' , function(){
  habilitarDesabilidadBtnAction();
})

$('.mask-all').on('change', function () {
  $("table").find('.mark-input').prop('checked', this.checked)
  habilitarDesabilidadBtnAction();
})

$('.mark-read,.delete-action').on('click', function (e) {
  
  console.log("mark read")
  if(!confirm('Esta seguro de esta acción?')) {
    e.preventDefault();
    return false;
  }

  $("#load_screen").show();

  
  let url = $(this).attr('data-url');
  let data = {
    'ids' : []
  };


  $("table .mark-input:checked").each(function(index,dom){
    let value = dom.value;
    data['ids'].push(value);
  });

  // 
  console.log('data.ids', url, data.ids );

  let funcs = {
    success : function(data){
      console.log(data);
      location.reload();
    },
    complete : function(){
      $("#load_screen").hide();
    }
  }

  ajaxs(data,url,funcs)
  
})





$("table.notificaciones").DataTable({
  "pageLength": 100
});


window.init(events);