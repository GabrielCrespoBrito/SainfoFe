
function initDatable() {
  table = $('#datatable').DataTable({
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, "desc"]],
    "ajax": {
      "url": $('#datatable').attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "empresa_id": $("[name=empresa_id]").val(),
        });
      }
    },
    "columns": [
      { data: 'user.usulogi' },
      { data: 'local.LocNomb' },
      { data: 'column_defecto' },
      { data: 'column_accion' },
    ]
  });
}

function changeEmpresa() {
  let data = {
    'empresa_id': $('[name=empresa_id]').val(),
  }
  let url = $('[name=empresa_id]').attr('data-url-change');
  ajaxs(data, url, {});
}

function events() 
{
  // |----------|----------|----------|----------|----------|
  $("[name=empresa_id]").on('change', function () {
    changeEmpresa()
    table.draw();

    let empresa_id = $("[name=empresa_id] option:selected").val();
    let url = $(".btn-create-local")
    .attr('data-route')
    .replace('---',empresa_id );

    $(".btn-create-local").attr( 'href' , url )

  })

}

init(initSelect2, initDatable, events);

Helper.init();
