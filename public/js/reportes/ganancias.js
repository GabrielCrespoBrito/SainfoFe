function putDataInTable(html)
{
   $("#div-table")
  .empty()
  .html(html);
}

function searchInfoReport()
{
  let $form = $("#form-reporte");

  $("#load_screen").show();

  let data = $form.serialize();
  let url = $form.attr('action');
  let funcs = {    success : putDataInTable,
    complete : () => {
      $("#load_screen").hide();
    }
  }

  ajaxs(data, url, funcs )
}

function showInnerInfo(e)
{
  e.preventDefault();

  let $this = $(this);
  let $span = $this.find('span');

  let $tr = $this.parents('tr');
  let $trNext = $tr.next('.tr-container-info');

  $span.removeClass();

  if( $trNext.is(':visible') ){
    $trNext.hide(500);
    $span.addClass('fa fa-eye');
  }
  else {
    $trNext.show(500);
    $span.addClass('fa fa-eye-slash');
  }
}

// Events
function events()
{
  $(".generate-report ").on('click', searchInfoReport )
  $("#div-table ").on('click', '.span-info', showInnerInfo )
}

events();
datepicker();


