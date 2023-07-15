@view_data([
'layout' => 'layouts.master_admin',
'title' => $title,
'titulo_pagina' => $title,
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','admin/mix/documentos_mix.js' ]]
])

@slot('titulo_small')
<a href="{{ route('admin.resumenes.pending') }}" class="link-pendientes"> <span class="fa fa-external-link"></span> Pendientes </a>
@endslot


@slot('contenido')
<div class="filtros">
  @include('admin.resumenes.partials.filtros', ['isPendiente' => false, 'showFiltroDocs' => true ])
  @include('admin.resumenes.partials.table', ['isPendiente' => false])

@endslot

@endview_data