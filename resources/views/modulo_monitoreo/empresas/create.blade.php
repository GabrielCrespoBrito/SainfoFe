@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Monitoreo de Documentos',
  'titulo_pagina'  => 'Nueva Empresa', 
  'bread'  => [ ['Monitoreo de Documentos', route('monitoreo.empresas.index')] , ['Crear'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','monitoreo/empresas.js']]
])

@slot('contenido')

  @include('modulo_monitoreo.empresas.partials.form', ['isCreate' => true, 'route' => route('monitoreo.empresas.store') , 'method' => method_field('POST')  ])

@endslot

@endview_data