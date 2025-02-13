function initDatatableVentas()
{
  let columns = [
    { data: 'codigo', orderable: false, searchable: false },
    { data: 'fecha', orderable: false, searchable: false },
    { data: 'compra', orderable: false, searchable: false },
    { data: 'venta', orderable: false, searchable: false },
  ];

  let options = {
    "pageLength": 10,
    "responsive": true,
    "processing": true,
    "oLanguage": {
      "sSearch": "", "sLengthMenu": "_MENU_",
      "sEmptyTable": "No se encuentra Tipo de cambio"
    },
    "serverSide": true,
    "ajax": {
      "url": $("#datatable-documentos").attr('data-url'),
      "data": function (d) {
        return $.extend({}, d, {
          // "tipo_documento": $("[name=tipo_documento] option:selected").val(),
          // "estado_almacen": $("[name=estado_almacen] option:selected").val(),
        });
      }
    },
    "columns": columns
  }

  table = $('#datatable-documentos').DataTable(options);
}




function initFuncs() {
  initSelect2();
}

function initPicker(format = "yyyy-mm-dd") {
  $('[name=fecha_desde],[name=fecha_hasta]').datepicker({
    autoclose: true,
    format: format,
  });
}

function esFechaNoMayorQueHoy(fecha) {
  const fechaAComprobar = new Date(fecha);
  const fechaHoy = new Date();

  // Set hours to 00:00:00 to compare only dates
  fechaAComprobar.setHours(0, 0, 0, 0);
  fechaHoy.setHours(0, 0, 0, 0);

  return fechaAComprobar < fechaHoy;
}

function events()
{
  $(".buscar-fecha").on('click', function () {
    let $this = $(this);
    let $input = $this.siblings('input');
    const fecha = $input.val();

    if (!esFechaNoMayorQueHoy(fecha)) {
      alert("No se puede consultar una fecha mayor a la actual");
      return;
    }

    const url = $input.attr('data-route');
    let urlLink = new URL(url);
    urlLink.searchParams.set('fecha', fecha);
    urlLink.searchParams.set('search', 'true');
    window.location.href = urlLink.toString();
  });


}

window.consultLocals = consultLocals;

window.init(
  initDatatableVentas, 
  events, 
  initPicker
);