
@php  
  $data_info;
  if(!$data_info = json_encode($info)){
    $info_arr = $info;
    $data_info = json_encode($info_arr);
  }
@endphp

<tr class="tr_item" data-unidades='{{ $unidades }}' data-info="{{ $info }}">
  <td class="DetItem" data-campo="DetItem">{{ $item }} </td>        
  <td class="TieCodi" data-campo="TieCodi">{{ $tiecodi }} </td>
  <td class="DetBase" data-campo="DetBase">{{ $base }} </td>
  <td class="UniCodi" data-campo="UniCodi">{{ $detcodi }} </td>    
  <td class="DetUnid" data-campo="DetUnid">{{ $unidad_nombre }} </td>    
  <td class="DetNomb" style="text-align:left" data-campo="DetNomb">{{ $nombre }} </td> 
  <td class="Marca" data-campo="Marca">{{ $marca }} </td> 
  <td class="DetCant text-right" data-campo="DetCant">{{ $cantidad }} </td>
  <td class="DetPrec text-right" data-campo="DetPrec">{{ $precio  }} </td>
  <td class="DetDcto text-right" data-campo="DetDcto">{{ $descuento }}  </td>
  <td class="DetImpo text-right" data-campo="DetImpo">{{ $importe  }} </td>  
  @if( $accion )          
  <td class="Accion text-right" data-campo="Accion">
    <a href='#' class='btn modificar_item btn-xs btn-primary'> <span class='fa fa-pencil'></span></a>
    <a href='#' class='btn eliminar_item btn-xs btn-danger'> <span class='fa fa-trash'></span> </a>
  </td>  
  @endif
</tr>	