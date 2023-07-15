@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Notificaciones',
'titulo_pagina' => 'Notificaciones',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => [ 'datepicker' , 'select2' , 'datatable' ],'js' => ['helpers.js','admin/mix/notificaciones.js' ]]
])

@slot('titulo_small')
{{-- <a href="{{ route('admin.guias.pending') }}" class="link-pendientes"> <span class="fa fa-external-link"></span> Pendientes </a> --}}
@endslot

@slot('contenido')
<div class="filtros">
  @include('admin.notificaciones.partials.filtros')
  @include('admin.notificaciones.partials.table')
</div>
@endslot

@endview_data