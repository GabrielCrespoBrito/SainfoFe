@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Resumen de contingencia',
  'titulo_pagina'  => 'Nuevo Resumen de contingencia', 
  'bread'  => [ ['Resumen de contingencia', route('contingencia.index') ] , ['Nuevo'] ],  
  'assets' => [ 'libs' => ['datepicker'] , 'js' => ['helpers.js','contingencia/scripts.js']]
])

@slot('contenido')
	@include('contingencia.partials.form', ['show' => false , 'action' => 'create'])
@endslot  

@endview_data
