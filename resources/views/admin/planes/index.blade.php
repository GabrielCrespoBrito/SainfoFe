@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Planes de Suscripcion',
'titulo_pagina' => 'Planes de Suscripcion',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'planes/adm_planes.js']]
])

@slot('contenido')
  @include('admin.planes.partials.filtros')
  @include('admin.planes.partials.table')
@endslot

@endview_data