<div class="container header">
<table id="header" width="100%">
  <tr>
    <td colspan="3" class="titulo">Kardex fisico</td>
  </tr>
  <tr>
    <td> {{ $empresa['EmpNomb'] }}</td>
    <td><span class="campo">Desde:</span> {{  $fecha_desde }}</td>
    <td><span class="campo">Hasta:</span> {{  $fecha_hasta }}</td>    
  </tr>
  <tr>
    <td><span class="campo">{{  $empresa["EmpLin1"] }}</span></td>
    <td><span class="campo">Articulo Inicio:</span> {{ $articulo_desde }} </td>
    <td><span class="campo">Articulo Final:</span> {{  $articulo_hasta }} </td>    
  </tr> 
  <tr>
    <td colspan="3"><span class="campo">Local</span> {{ $nombre_local }}  </td>
  </tr>  
</table>
</div>