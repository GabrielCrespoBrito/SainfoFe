@view_data([
'layout' => 'layouts.master_admin',
'title' => $titulo,
'titulo_pagina' => $titulo ,
'bread' => [ ['Empresas' , route('admin.empresa.index') ], ['Empresas' , route('admin.empresa.index') ] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js' , 'empresa/empresa.js',  'empresa/form_cert.js' ]]
])

@slot('titulo_small')

{{-- ooooooo AWS ooooooo --}}
@if( $empresa->isActive() )
  <small class="label pull-right bg-green">Activa</small>
@else
  <small class="label pull-right bg-red">Inactiva</small>
@endif

@endslot

@slot('contenido')

@include('partials.errors_html')

@include('admin.empresa.partials.form', [ 'form_sunat' => true, 'form_visualizacion' => true, 'form_parametros' => true, 'form_acciones' => true, 'form_certificado' => true, 'form_tienda' => true ])

@endslot
@endview_data