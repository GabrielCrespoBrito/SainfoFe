@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Modificar Resumen de contingencia',
  'titulo_pagina'  => 'Modificar Resumen de contingencia', 
  'bread'  => [ ['Resumen de contingencia', route('contingencia.index') ] , ['Modificar'] ],  
  'assets' => [ 'libs' => ['datepicker'] , 'js' => ['helpers.js','contingencia/scripts.js']]
])

@slot('contenido')
	@include('contingencia.partials.form', ['show' => false , 'action' => 'edit'])
@endslot  

@endview_data
