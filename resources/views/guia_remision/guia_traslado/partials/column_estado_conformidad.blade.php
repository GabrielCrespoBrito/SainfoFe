@if( $model->isConformidadPendiente() )

  <a href="#" class="btn btn-xs btn-flat btn-default disabled">
    <span class="fa fa-spinner fa-spin"></span> Pendiente
  </a>

@elseif( $model->isConformidadAceptado() )

  <a href="#" class="btn btn-xs btn-flat btn-success disabled">
    <span class="fa fa-check"></span> Aceptado </a>

@elseif( $model->isConformidadRechazado() )

  <a href="#" class="btn btn-xs btn-flat btn-danger disabled">
  <span class="fa fa-ban"></span> Rechazado </a>

@endif

  <a href="#" title="" class="btn btn-xs btn-flat btn-primary modal-conformidad"> <span class="fa fa-pencil"></span> </a>