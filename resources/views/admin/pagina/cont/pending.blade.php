@view_data([
'layout' => 'layouts.master_admin' ,
'title' => $title,
'titulo_pagina' => $title,
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','admin/mix/documentos_mix.js' ]]
])

@slot('titulo_small')
  <a href="{{ route('admin.guias.index') }}" class="link-pendientes"> <span class="fa fa-list-ul"></span> Todos </a>
@endslot

@slot('contenido')

  @if($hasPendientes)
    @include('admin.guias.partials.filtros', ['isPendiente' => true, 'showFiltroDocs' => false ])
    @include('admin.guias.partials.table', ['isPendiente' => true ])
  @else
    @include('admin.documentos.partials.no_data', ['name' => 'Guias Remisión', 'route' => route('admin.actions.update_guias_acciones') ])
  @endif

@endslot

@endview_data