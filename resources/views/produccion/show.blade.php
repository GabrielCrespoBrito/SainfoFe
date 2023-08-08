@view_data([
  'layout' => 'layouts.master',
  'title'  =>  $produccion->manId,
  'titulo_pagina' => $produccion->manId,
  'bread'  => [ ['Producción Manual', route('produccion.index')] , [$produccion->manId] ],
  'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js', 'produccion/script.js']]
])

@slot('contenido')
  @include('produccion.partials.form.form', ['accion' => 'show' ])
@endslot  

@endview_data