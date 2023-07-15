<div class="filtros">

  @include('admin.partials.filtros_empresa')
  
  <hr>

  @if($isPendiente)
    @include('admin.partials.data_pendientes', ['routeUpdatePendiente' => route('admin.actions.update_ventas_acciones')])
  @endif

  @if(!$isPendiente )
    @include('admin.partials.filtros_documentos')
  @endif

</div>