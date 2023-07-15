@view_data([
  'layout' => 'layouts.master' ,
  'title' => 'Nuevo Local',
  'titulo_pagina' => 'Nuevo Local',
  'bread' => [ ['Locales', route('locales.index')] , ['Crear'] ],
  'assets' => [ 'libs' => ['select2'], 'js' => ['helpers.js','locales/create.js' ]]
])

@slot('contenido')
  {{-- @dd("Aqui estamos y aqui segumos") --}}

  @include('locales.partials.form', ['accion' => 'create' ])
@endslot

@endview_data