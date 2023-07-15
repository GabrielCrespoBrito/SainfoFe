@view_data([
'layout' => 'layouts.master',
'title' => $title ,
'titulo_pagina' => $title,
'bread' => [ [ $title ] ,],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','cajas/cuentasporpagar.js', 'cajas/pagos.js' ]]
])

@slot('js_before')
  <script type="text/javascript">
    window.tipo = "{{ $tipo }}"
    window.url_search = "{{ $url_search }}"
    window.url_cliente = "{{ route('clientes.buscar_cliente_select2') }}";
    window.url_reporte = "{{ route('cajas.cuentas_pdf', $tipo) }}";
    window.url_send_email = "{{ route('mails.deudasCliente') }}";
  </script>
@endslot

@push('js')
  <script type="text/javascript">
    window.url_update_totals = "{{ route('cajas.totales', $isPorPagar ) }}"
    $(document).ready(function(index) {
      updateTotals();
      AppPagosIndex.init();
      AppPagosIndex.callSuccessRemove = function(data) {
        reloadTable();
      };
      function reloadTable() {
        table.draw();
      }
      $("#reloadTable").on('click', reloadTable)
      window.table.on('draw.dt', updateTotals);
      $("#datatable").on('click', ".pagar", function() {
        let id = $(this).parents('tr').find("td.model_id").text();
        let urlPagos = $("#modalPagos .botones_div").attr('data-urlCopypagos').replace('XXX', id);
        $("#modalPagos .botones_div").attr({
          'data-urlpagos': urlPagos
        , })
        AppPagosIndex.set_urls()
        AppPagosIndex.set_id(id);
        AppPagosIndex.show_openmodal();
        let newUrl = $("#modalPago").attr('data-urlStatus').replace('XXX', id);
        $("#modalPago").attr('data-urlpagos', newUrl);
        let callBack = function(data) {
          let func = AppPagosIndex.show_notopenmodal.bind(AppPagosIndex)
          func(data);
          reloadTable();
        }
        AppPago.set_callback(callBack);
      });
    })
  </script>
@endpush


@slot('contenido')
@include('cajas.partials.filter')

@component('components.table', [ 'id' => 'datatable', 'thead' => ['RUC', 'Razon Social', 'F. Emisión', 'F. Venc' , 'N° Oper' , 'T.Doc' , 'Nro Doc', 'Moneda', 'Saldo' , 'Total', 'Pagado' , 'Acciones'] ])
@endcomponent

@include('cajas.partials.totals')
@include('ventas.partials.modal_pagos_comp', ['type' => $type_pago, 'compra' => $compra, 'venta' => $venta ])
@include('ventas.partials.modal_pago',['type' => $type_pago, ['tipos_pagos' => $tiposPagos]])
@include('cotizaciones.partials.modal_redactar_correo' , ['asunto' => 'Reporte de deudas'])

@endslot

@endview_data