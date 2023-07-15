<div class="filtros">

  @include('admin.partials.filtros_empresa')
  <hr>

  @if($isPendiente)
    @include('admin.partials.data_pendientes', ['routeUpdatePendiente' => route('admin.actions.update_resumenes_acciones')])
    <hr>
    @include('admin.resumenes.partials.botones_pendientes')
  @endif

  @if( $showFiltroDocs )
    @include('admin.resumenes.partials.filtros_data')
  @endif

</div>
