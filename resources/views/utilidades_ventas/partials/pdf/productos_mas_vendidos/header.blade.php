<div class="container header">
<table id="header" width="100%">

  <tr>
    <td colspan="4" style="text-align: center; font-weight: bold;" class="titulo"> Productos mas vendidos </td>
  </tr>

  <tr>
    <td class="strong">{{ $empresa['EmpNomb'] }}</td>
    <td><span class="campo">Desde:</span> {{ $fecha_desde }}</td>
    <td><span class="campo">Hasta:</span> {{ $fecha_hasta }}</td>    
    <td><span class="campo">Local:</span> {{ $local }}</td>        
  </tr>

  <tr>
    <td colspan="4"><span class="campo strong">{{ $empresa["EmpLin1"] }}</span></td>
  </tr> 



</table>
</div>