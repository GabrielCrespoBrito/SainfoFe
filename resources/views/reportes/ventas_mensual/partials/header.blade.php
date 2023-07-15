@php
  $titulo = $titulo ?? 'Registro de ventas';
@endphp
<div class="container header">
<table id="header" width="100%">
  <tr>
    <td class="titulo">  {{ $titulo }} </td>
    <td>  </td>
  </tr>

  <tr>
    <td> <strong> {{ $nombre_empresa }} </strong>  {{ $ruc_empresa }} </td>
    <td> <strong> FECHA REPORTE: </strong> {{ datePeru('Y-m-d H:i:s') }} </td>
  </tr>

  <tr>
    <td> <strong> Periodo: </strong> {{ $periodo }} </td>
    <td> <strong> Moneda: </strong> SOLES </td>
  </tr> 

</table>
</div>