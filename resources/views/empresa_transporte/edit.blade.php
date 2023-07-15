@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar empresa de transporte',
  'titulo_pagina'  => 'Modificar empresa de transporte', 
  'bread'  => [ ['Empresas de transporte' , route('empresa_transporte.index') ] , ['Modificar'] ],
])

@slot('contenido')
  @include('empresa_transporte.partials.form')
@endslot  

@endview_data


