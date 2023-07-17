<td class="value-name text-center"> 
  <a href="#" style="{{ $cant ? '' : 'pointer-events:none' }}" class="btn btn-block btn-xs btn-default btn-flat {{ $cant ? 'btn-status-change' : ''  }}" 
  data-tipo={{ $docCodi }}
  data-status="{{ $status }}"> 
  @if($cant)
  <span class="fa fa-hand-pointer-o"> </span> 
  @endif
  {{ $cant }} </a> 
</td>

<td class="value-data text-right"> 
  <a href="#" style="background-color:#97ff97; color:black; pointer-events:none" class="btn btn-xs btn-block btn-flat"> {{ $total }} </a> 
</td>