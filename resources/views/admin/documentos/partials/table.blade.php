<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
  <table 
    data-url="{{ $routeTableSearch }}" 
    style="width: 100% !important;"
    id="datatable-documentos"
    data-type="documentos"
    data-url-sunat="{{ route('admin.documentos.send_pendientes') }}"
    data-url-date="{{ route('admin.documentos.change_date', '@@@@') }}"
    data-url-consult-status="{{ route('admin.documentos.consult_status') }}"
    class="table sainfo-table sainfo-noicon datatable-pendiente oneline pt-0 {{ $isPendiente ? 'is-pendiente' : '' }}">
    <thead>
      <tr>
        <td class="nro_venta" style="width:90px"> N° Venta </td>
        <td class="td" style="width:25px"> T.D </td>
        <td class="doc" style="width:50px"> N° Doc </td>
        <td class="fecha" style="width:100px"> Fecha </td>
        <td class="clien3"> Cliente </td>
        <td class="Moneda"> Mon </td>
        <td class="Importe text-right-important" style="text-align:right!important"> Importe </td>
        @if( ! $isPendiente )
        <td class="Pago"> Pago </td>
        <td class="Saldo"> Saldo </td>
        <td class="Guia"> Est. Almacen </td>
        <td class="Estado text-center"> Estado </td>
        @endif
        <td class="acciones"> &nbsp </td>
      </tr>
    </thead>
  </table>
</div>