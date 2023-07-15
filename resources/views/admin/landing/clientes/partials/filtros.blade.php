<div class="filtros">

  @include('admin.partials.filtros_empresa')
  <hr>

  @if($isPendiente)
    @include('admin.partials.data_pendientes', ['routeUpdatePendiente' => route('admin.actions.update_guias_acciones')])
    <hr>
    @include('admin.documentos.partials.botones_pendientes', ['isPendiente' => false ])
  @endif

  @if( $showFiltroDocs )
    @include('admin.guias.partials.filtros_data')
  @endif

</div>
