{{-- Si es nota de venta --}}
@if( $TidCodi == "52" )

<a href="#" class="btn btn-xs {{ $VtaEsta == "A" ?  'anulada' : 'aceptado' }}">
  <span class="fa fa-check"></span>
  {{ $VtaEsta == "A" ?  'Anulada' : 'Aceptado' }}
</a>

@else

@if( $VtaEsta == "A" )

<a href="#" class="btn btn-xs anulada">
  <span class="fa fa-spin"></span>
  Anulada
</a>

@elseif( $fe_rpta == 0 )

<a href="#" class="btn btn-xs aceptado">
  <span class="fa fa-check"></span> Aceptado
</a>

@elseif( $fe_rpta == 2 )

<a href="#" class="btn btn-xs por_rechazado">
  <span class="fa fa-times"></span>
  Rechazado
</a>

@elseif( $fe_rpta == 9 )

<a href="#" class="btn btn-xs por_enviar">
  <span class="fa fa-spin fa-spinner"></span>
  Por enviar
</a>

@else

<a href="#" class="btn btn-xs btn-danger">
  <span class="fa fa-ban"></span> Con error
</a>

@endif

@endif