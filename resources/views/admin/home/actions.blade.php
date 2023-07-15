<div class="col-md-6">
<div class="box {{ $hasPendientes ? 'box-danger' : 'box-success' }}  action-box">
  <div class="box-header with-border">
    <i class="circle {{ $hasPendientes ? 'red' : 'green' }}"></i>
    <h3 class="box-title"> {{ $hasPendientes ? 'ACCIONES PENDIENTES' : 'NO HAY ACCIONES PENDIENTE'  }} </h3>
    
    <a href="{{ route('admin.actions.update_all_acciones') }}" class="pull-right btn-label label-date-all"> <span class="fa fa-refresh"></span> Actualizar </a>
    
    @if( $hasPendientes )
      <a href="{{ route('admin.comandos', 'enviar_doc_pendientes') }}" class="pull-right btn-label mr-x4"> <span class="fa fa-send"></span> Enviar Pendientes </a>
    @endif


  </div>
  <div class="box-body">
    @if($hasPendientes)
    @foreach( $acciones->value as $keyName => $accion )
    <div class="action-item">
      <div>
        <a target="_blank" href="{{ optional($accion)->route ? route($accion->route) : '#' }}"> {{ __( 'messages.acciones.'. $keyName) }} </a>
        <small class="label-date pull-right"> <span class="fa fa-clock-o"></span> {{ $accion->updated_at }} </small>
        <small class="label pull-right"> {{ $accion->cant }} </small>
      </div>
    </div>
    @endforeach
    @else
    <div class="no-acciones"> <span class="fa fa-check"></span> Todo en orden  </span>
      @endif
    </div>
  </div>
</div>
