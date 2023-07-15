@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Monitoreo',
  'titulo_pagina'  => 'Monitoreo', 
  'bread'  => [ 
    ['Monitoreo' , route('monitoreo.empresas.index') ] , 
    [ $empr->razon_social, route('monitoreo.empresas.show', $empr->id) ] , 
    ['Documentos'] 
  ],
  'assets' => ['libs' => ['datatable', 'select2'],'js' => ['helpers.js','monitoreo/documents.js']]
])

@slot('contenido')

  @include('modulo_monitoreo.documents.partials.filters' , ['series' => $empr->series, 'showCode' => true ])

  @component('components.table', [ 'id' => 'datatable' , 'url' => route('monitoreo.empresas.docs_search' , $empr->id)  , 'class_name' => 'sainfo-noicon size-9em', 'thead' => ['Serie' , 'N° Doc', 'RUC',  'Fecha', 'Estatus' , 'Mensaje'] ])
  @endcomponent

@endslot  

@endview_data
