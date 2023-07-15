@view_data([
'layout' => 'layouts.master',
'title' => 'Parametros Empresa',
'titulo_pagina' => 'Parametros Empresa' ,
'bread' => [ ['Empresas' , route('admin.empresa.index') ], ['Empresas' , route('admin.empresa.index') ] ],
'assets' => ['libs' => ['datepicker','select2'],'js' => ['helpers.js' , 'empresa/empresa.js' , 'empresa/form_cert.js' ]]
])

@slot('contenido')
@include('partials.errors_html')

  @include('admin.empresa.partials.form', [ 'form_sunat' => false, 'form_visualizacion' => true, 'form_parametros' => true, 'form_acciones' => false, 'form_certificado' => false, 'form_tienda' => false ])

@endslot
@endview_data