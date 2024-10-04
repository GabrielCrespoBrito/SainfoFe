@if( $model->UDelete == "*" )
  <a href="{{ route('grupos.restaurar', $model->GruCodi) }}" class="btn btn-xs btn-default btn-flat">  Restaurar </a> 
@endif