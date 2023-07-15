<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
  <table 
    data-url="{{ $routeTableSearch }}" 
    style="width: 100% !important;"
    id="datatable-resumenes"
    data-type="resumenes"
    data-url-sunat="{{ route('admin.resumenes.send_pendientes') }}"
    class="table sainfo-table sainfo-noicon datatable-pendiente oneline pt-0 {{ $isPendiente ? 'is-pendiente' : '' }}">
    <thead>
      <tr>
        <td> N° Oper </td>
        <td> Número </td>
        <td> Fecha </td>
        <td> Fecha Envio </td>
        <td> Documentos </td>
        <td> Ticket </td>
        <td> Estado Sunat </td>
        <td class="acciones"> &nbsp </td>
      </tr>
    </thead>
  </table>
</div>