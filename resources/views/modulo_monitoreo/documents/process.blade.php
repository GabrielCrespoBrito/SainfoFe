@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Consutar estado de documentos',
  'titulo_pagina'  => 'Consutar estado de documentos', 
  'bread'  => [ 
    ['Monitoreo' , route('monitoreo.empresas.index') ] , 
    [ $empr->razon_social, route('monitoreo.empresas.show', $empr->id) ] , 
    ['Documentos'] 
  ],
  'assets' => ['libs' => ['select2'],'js' => ['helpers.js','monitoreo/process_documents.js']]
])


@slot('contenido')

@include('modulo_monitoreo.documents.partials.empresas' , ['size' => 'col-md-10' , 
'showButtonDocument' => true , 
'routeName' => 'monitoreo.empresas.process_docs' , 
'routeButton' => 'monitoreo.empresas.docs' , 

$empr->id])

  @include('modulo_monitoreo.documents.partials.filters_process')
  @include('modulo_monitoreo.documents.partials.result_process')

  <div class="box-table-busqueda">

    <p class="title"> Documentos </p>
    @component('components.table', [ 'id' => 'table-busqueda' , 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Serie' , 'N° Doc', 'Codigo Estatus' , 'Mensaje' ] ])
    @endcomponent

  </div>
    
    @slot('footer_before')
    @include('components.block_elemento')
  @endslot

@endslot  

@endview_data

