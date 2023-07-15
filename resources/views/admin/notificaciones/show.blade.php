@view_data([
  'layout' => 'layouts.master_admin',
  'title' => $title,
  'titulo_pagina' => $title,
  'bread' => [ ['Inicio'], ['Notificaciones' , route('admin.notificaciones.index')], ['Notificacion'] ],
  'assets' => ['libs' => [ 'datepicker' , 'select2' , 'datatable' ],'js' => ['helpers.js','admin/mix/notificaciones.js']]
])

@slot('contenido')
  @include('admin.notificaciones.partials.form')
@endslot

@endview_data
