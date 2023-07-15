@view_data([
  'layout' => 'layouts.master' , 
  'title'  =>  'Monitoreo documentos' ,
  'titulo_pagina'  => $empr->razon_social, 
  'bread'  => [ ['Monitoreo de Documentos', route('monitoreo.empresas.index')] , [ $empr->razon_social ]  ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','monitoreo/empresas.js']]
])

@slot('contenido')

  @include('modulo_monitoreo.empresas.partials.form', ['isCreate' => false, 'route' => route('monitoreo.empresas.update',  $empr->id ) , 'method' => method_field('PUT')  ])

@endslot

@endview_data