<div class="container header">
<table id="header" width="100%">

  <tr>
    <td colspan="5" style="text-align: center; font-weight: bold;" class="titulo"> Productos mas vendidos </td>
  </tr>

  <tr>
    <td class="strong">{{ $empresa_nombre }}</td>
    <td><span class="campo">Desde:</span> {{ $fecha_desde }}</td>
    <td><span class="campo">Hasta:</span> {{ $fecha_hasta }}</td>    
    <td><span class="campo">Local:</span> {{ $local }}</td>        
    <td><span class="campo">Grupo:</span> {{ $grupo_nombre }}</td>        
  </tr>

  <tr>
    <td colspan="4"><span class="campo strong">{{ $empresa_ruc }}</span></td>
  </tr> 

</table>
</div>