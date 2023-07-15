<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
  <table 
    data-url="{{ $routeTableSearch }}" 
    style="width: 100% !important;"
    id="datatable-guias"
    data-type="guias"
    data-url-sunat="{{ route('admin.guias.send_pendientes') }}"
    class="table sainfo-table sainfo-noicon datatable-pendiente oneline pt-0 {{ $isPendiente ? 'is-pendiente' : '' }}">
    <thead>
      <tr>
        <td class="nro_venta" style="width:90px"> # </td>
        <td style="width:25px"> N° Doc </td>
        <td style="width:50px"> Doc Ref </td>
        <td> Fecha </td>
        <td> Cliente </td>
        <td> Almacen </td>
        <td> Estado </td>
        <td class="acciones"> &nbsp </td>
      </tr>
    </thead>
  </table>
</div>