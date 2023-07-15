@view_data([
'layout' => 'layouts.master_admin' ,
'title' => $title,
'titulo_pagina' => $title,
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','admin/mix/documentos_mix.js' ]]
])

@slot('titulo_small')
  <a href="{{ route('admin.documentos.index') }}" class="link-pendientes"> <span class="fa fa-list-ul"></span> Todos </a>
@endslot


@slot('contenido')

  @if($hasPendientes)
    @include('admin.partials.filtros', [ 'isPendiente' => true ])
    <hr>
    @include('admin.documentos.partials.botones_pendientes', ['isPendiente' => false ])
    @include('admin.documentos.partials.table', [ 'isPendiente' => true ])        
  @else 
    @include('admin.documentos.partials.no_data', [ 'name' => 'Documentos', 'route' => route('admin.actions.update_ventas_acciones')])

  @endif

@endslot

@endview_data