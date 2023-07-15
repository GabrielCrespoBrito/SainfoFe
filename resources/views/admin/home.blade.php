@view_data([
  'layout' => 'layouts.master_admin' , 
  'title'  => 'Inicio',
  'titulo_pagina'  => 'Inicio', 
  'bread'  => [ ['Inicio'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')
  @include('admin.home.system_info')

  <div class="row">
    @include('admin.home.actions')
    @include('admin.home.notificaciones')
  </div>

@endslot

@endview_data