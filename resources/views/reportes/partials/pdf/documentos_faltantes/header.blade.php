<div class="container header">
<table id="header" width="100%">
  <tr>
    <td colspan="3" class="titulo">
        Reporte documentos faltantes
  </td>
  </tr>
  <tr>
    <td> {{ $empresa['EmpNomb'] }}</td>
    <td><span class="campo">Desde:</span> {{  $fecha_desde }}</td>
    <td><span class="campo">Hasta:</span> {{  $fecha_hasta }}</td>    
  </tr>
  <tr>
    <td><span class="campo">{{  $empresa["EmpLin1"] }}</span></td>
    <td><span class="campo">Tipo documento:</span> {{  $tipo_documento }} </td>
    <td><span class="campo">Serie:</span> {{  $serie_documento }} </td>    
  </tr> 
</table>
</div>