@if( $model->UDelete == "*" )
  <a href="{{ route('marcas.restaurar', $model->MarCodi) }}" class="btn btn-xs btn-default btn-flat">  Restaurar </a> 
@endif