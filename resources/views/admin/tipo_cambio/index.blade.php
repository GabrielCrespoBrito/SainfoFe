@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Tipo de Cambio',
'titulo_pagina' => 'Tipo de Cambio',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'admin/mix/tipo_cambio_mix.js' ]]
])

@slot('titulo_small')
  <a href="{{ route('admin.tipo_cambio.index', ['search' => 'true', 'fecha' => date('Y-m-d')]) }}" class="link-pendientes"> <span class="fa fa-search"></span> Buscar Tipo de Cambio </a>
@endslot

@slot('contenido')
@if($search)
  @include('partials.errors_html')
  @include('admin.tipo_cambio.partials.form', [])
@endif
  @include('admin.tipo_cambio.partials.table', ['routeTableSearch' => route('admin.tipo_cambio.search')])
@endslot

@endview_data
