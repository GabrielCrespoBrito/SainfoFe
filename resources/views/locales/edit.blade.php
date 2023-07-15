@view_data([
  'layout' => 'layouts.master' ,
  'title' => 'Modificar Local',
  'titulo_pagina' => 'Modificar Local',
  'bread' => [ ['Modificar Local', route('locales.index')] , ['Modificar'] ],
  'assets' => [ 'libs' => ['select2'], 'js' => ['helpers.js','locales/create.js' ]]

])

@slot('contenido')
  @include('locales.partials.form', ['accion' => 'edit' ])
@endslot

@endview_data