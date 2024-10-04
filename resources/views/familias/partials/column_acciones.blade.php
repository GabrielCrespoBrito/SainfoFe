@if( $model->UDelete == "*" )
  <a href="{{ route('familias.restaurar',   [ 'id' => $model->famCodi, 'id_grupo' => $model->gruCodi ] ) }}" class="btn btn-xs btn-default btn-flat">  Restaurar </a>  
@endif