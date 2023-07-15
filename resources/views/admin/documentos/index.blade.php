@view_data([
'layout' => 'layouts.master_admin' ,
'title' => $title,
'titulo_pagina' => $title,
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'admin/mix/documentos_mix.js' ]]
])

@slot('titulo_small')
  <a href="{{ route('admin.documentos.pending') }}" class="link-pendientes"> <span class="fa fa-external-link"></span> Pendientes </a>
@endslot

@slot('contenido')
  @include('admin.partials.filtros', ['isPendiente' => false ])
  @include('admin.documentos.partials.table', ['isPendiente' => false ])
@endslot

@endview_data
