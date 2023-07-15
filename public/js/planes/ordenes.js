function initDataTable() {

  let options = {
    "pageLength": 25,
    "responsive": true,
    "processing": true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra Orden de Pago"
    },
    "serverSide": true,
    "ajax": {
      "url": $("#datatable").attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          "estatus": $("[name=estatus] option:selected").val(),
        });
      }
    },
    "columns": [
      { 'data': 'link' },
      { 'data': 'fecha_emision' },
      { 'data': 'plan' },
      { 'data': 'empresa' },
      { 'data': 'usuario' },
      { 'data': 'total' },
      { 'data': 'estado' },
      { 'data': 'accion' },
    ]
  }
  table = $('#datatable').DataTable(options);
}


function events()
{  
  $("[name=estatus]").on('change', function () {
    table.draw();
  })
}

window.init(
  events,
  initDataTable,
);