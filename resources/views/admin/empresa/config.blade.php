@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Configuración para envio de documentos electronicos',
  'titulo_pagina'  => 'Configuración para envio de documentos electronicos', 
  'bread'  => [ ['Configuración'] ],
  'assets' => ['js' => ['helpers.js', 'helper.js', 'empresa/empresa.js', 'empresa/form_cert.js' ]]
])

@slot('contenido')
	@include('empresa.partials.parametros.form_certificado')
@endslot  

@endview_data

