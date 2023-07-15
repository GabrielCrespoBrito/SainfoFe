@if( $model->isTrasladoPendiente() )
  <a href="#" class="btn btn-xs btn-flat btn-default disabled"> 
    <span class="fa fa-spinner fa-spin"></span> Pendiente
  </a>

  <a href="#" title="Realizar Traslado" class="btn btn-xs btn-flat btn-primary trasladar-guia">
    <span class="fa fa-pencil"></span> </a>

@else
  <a href="#" class="btn btn-xs btn-flat btn-success disabled">
    <span class="fa fa-check"></span> Cerrado
  </a>
@endif