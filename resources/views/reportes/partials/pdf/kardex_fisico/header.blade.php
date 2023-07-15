<div class="header">

<table  width="100%">
  <tr>
    <td colspan="3" class="titulo"> KARDEX FISICO </td>
  </tr>
</table>

<table id="header" width="100%">

  <tr>
    <td width="20%"> <span class="campo"> {{ $nombre_empresa  }} </span> </td>
    <td width="20%"> <span class="campo"> {{ $ruc_empresa }} </span> </td>
    <td width="60%"></td>    
  </tr>
  
  <tr>
    <td><span class="campo"> Fecha desde:</span> </td>
    <td> {{  $fecha_desde }}</td>
    <td></td>    
  </tr>
  
  <tr>
    <td><span class="campo"> Fecha hasta:</span> </td>
    <td> {{  $fecha_hasta }}</td>
    <td></td>    
  </tr>

  @if($articulo_desde)
  <tr>
    <td><span class="campo"> Articulo Inicial:</span> </td>
    <td> {{ $articulo_desde }} </td>
    <td></td>    
  </tr>

  <tr>
    <td><span class="campo"> Articulo Final:</span> </td>
    <td> {{ $articulo_hasta }} </td>
    <td></td>    
  </tr>
  @endif

  <tr>
    <td><span class="campo"> Local:</span> </td>
    <td> {{ $nombre_local }} </td>
    <td></td>    
  </tr>


</table>

</div>