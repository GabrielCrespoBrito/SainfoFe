@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Nueva Empresa de transporte',
  'titulo_pagina'  => 'Nueva Empresa de transporte', 
  'bread'  => [ ['Nueva Empresa de transporte' , route('empresa_transporte.index') ], ['Crear'] ],
])

@slot('contenido')
  @include('empresa_transporte.partials.form')
@endslot  

@endview_data


