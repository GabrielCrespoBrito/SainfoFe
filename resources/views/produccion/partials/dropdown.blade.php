<div class="dropdown">
  <button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Acciones <span class="caret"></span>
  </button>
  <ul class="dropdown-menu sainfo">
  
    <li><a href="{{ route('produccion.show', $produccion->manId) }}"> <span class="fa fa-eye"></span> Ver </a></li>
  
  @if( !($produccion->isEstadoCulminado() || $produccion->isEstadoAnulado()) )

    <li><a href="{{ route('produccion.edit', $produccion->manId) }}"> <span class="fa fa-pencil"></span> Modificar </a></li>
  @endif
    
  @if( !$produccion->isEstadoCulminado() )

    <li><a class="btn-eliminar" href="{{ route('produccion.destroy', $produccion->manId) }}"> <span class="fa fa-trash" href="{{ route('produccion.destroy', $produccion->manId) }}"></span> Eliminar </a></li>
  @endif


  </ul>
</div>