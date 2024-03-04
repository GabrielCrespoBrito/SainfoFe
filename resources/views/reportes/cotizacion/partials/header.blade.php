
<table class="table-header-informacion" width="100%">

  <tr class="font-size-7">
    <td with="25%">
      <span class="bold"> Vendedor: </span> <span> {{ $info['vendedor'] }} </span>
    </td>
    
    <td with="25%">
      <span class="bold"> Usuario: </span> <span> {{ $info['usuario'] }} </span>
    </td>

    <td with="50%">
      <span class="bold"> Estado: </span> <span> {{ $info['estado'] }} </span>
    </td>    
  </tr>

  <tr class="font-size-7">
    <td>
      <span class="bold"> Fecha desde: </span> <span> {{ $info['fecha_desde'] }} </span>
    </td>

    <td colspan="2">
      <span class="bold"> Fecha hasta: </span> <span> {{ $info['fecha_hasta'] }} </span>
    </td>
  </tr> 
</table>