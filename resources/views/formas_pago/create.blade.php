@view_data([
  'layout' => 'layouts.master' ,
  'title' => 'Forma Nueva de pago',
  'titulo_pagina' => 'Forma Nueva de pago',
  'bread' => [ ['Forma de pago', route('formas-pago.index')] , ['Crear'] ],
  'assets' => ['js' => ['helpers.js','forma_pago/create.js' ]]
])

@slot('contenido')
  @include('formas_pago.partials.form', ['accion' => 'create' ])
@endslot

@endview_data