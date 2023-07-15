@view_data([
  'layout' => 'layouts.master',
  'title'  => 'Compras',
  'titulo_pagina' => 'Compras',
  'bread'  => [ ['Compras', route('compras.index')] , ['Crear'] ],
  'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','compras/mix/crud_mod.js', 'clientes/scripts.js'  ]]
])

@slot('contenido')
  @include('compras.partials.form.form', ['accion' => 'create' ])
@endslot  

@endview_data