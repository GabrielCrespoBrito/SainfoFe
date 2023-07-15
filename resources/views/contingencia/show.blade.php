@view_data([
  'layout' => 'layouts.master' , 
  'title'  => $contingencia->docnume,
  'titulo_pagina'  => $contingencia->docnume, 
  'bread'  => [ ['Resumen de contingencia', route('contingencia.index') ] , [$contingencia->docnume ]],  
  'assets' => [ 'libs' => ['datepicker'] , 'js' => ['helpers.js','contingencia/scripts.js']]
])

@slot('contenido')
	@include('contingencia.partials.form', ['show' => true , 'action' => 'show'])
@endslot  

@endview_data
