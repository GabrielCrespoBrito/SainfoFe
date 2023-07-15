{{-- @dd( $notificacion->data ) --}}

<div class="row mb-x10">
  <div class="col-md-12">
  <div class="form-group">
    <label class="col-sm-2 control-label"> </span> Estado </label>
    <div class="col-sm-8">
      <div> {{ $notificacion->readEstado() }}</div>
    </div>
  </div>
  </div>
</div>


<div class="row mb-x10">
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-sm-2 control-label"> Título </label>
      <div class="col-sm-8">
        <div> {{ $notificacion->data['titulo'] }} </div>
      </div>
    </div>
  </div>
</div>

<div class="row mb-x10">
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-sm-2 control-label"> Descripción </label>

      <div class="col-sm-8">
        <div>{!! $notificacion->data['descripcion'] !!}</div>
      </div>
    </div>
  </div>
</div>

<div class="row mb-x10">
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-sm-2 control-label"> Fecha Notificación </label>
      <div class="col-sm-8">
        <div> {{ $notificacion->created_at  }} </div>
      </div>
    </div>
  </div>
</div>


<div class="row mb-x10">
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-sm-2 control-label"> Fecha Lectura </label>
      <div class="col-sm-8">
      <div> {{ $notificacion->read_at  }} </div>
      </div>
    </div>
  </div>
</div>



<div class="row mb-x10">
  <div class="col-md-8 col-md-offset-2">
      @if( $notificacion->read() )
      <a href="{{ route('admin.notificaciones.unread', $notificacion->id) }}" title="Cambiar a Pendiente" class="btn btn-default btn-flat"> <span class="fa fa-refresh"></span> Cambiar estado a Pendiente </a>
      @else
      <a href="{{ route('admin.notificaciones.read', $notificacion->id) }}" title="Cambiar a Leida" class="btn btn-success btn-flat"> <span class="fa fa-check"></span> Cambiar estado a leido </a>
      @endif

      <a href="{{ route('admin.notificaciones.delete', $notificacion->id) }}" title="Cambiar a Leida" class="btn btn-default btn-flat ml-x10"> <span 
      class="fa fa-trash"></span> Eliminar </a> 

      <a href="{{ route('admin.notificaciones.index') }}" class="btn btn-default btn-flat pull-right"> Volver </a>



  </div>
</div>


