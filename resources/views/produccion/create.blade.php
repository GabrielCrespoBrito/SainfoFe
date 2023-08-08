@view_data([
  'layout' => 'layouts.master',
  'title'  => 'Producción Manual - Nueva',
  'titulo_pagina' => 'Producción Manual - Nueva',
  'bread'  => [ ['Producción Manual', route('produccion.index')] , ['Crear'] ],
  'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'produccion/script.js']]
])

@slot('contenido')
  @include('produccion.partials.form.form', ['accion' => 'create' ])
@endslot  

@endview_data