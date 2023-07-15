@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Monitoreo de Documentos',
  'titulo_pagina'  => 'Monitoreo Documentos - Empresas', 
  'bread'  => [ ['Monitoreo de Documentos'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','monitoreo/empresas.js']]
])

@slot('contenido')

  <div class="row">
    <div class="col-md-12" style="margin-bottom: 10px">
      <a href="{{ route('monitoreo.empresas.docs') }}" class="btn btn-primary btn-flat"> Buscar Documentos </a>    
      <a href="{{ route('monitoreo.empresas.process_docs') }}" class="btn btn-default btn-flat"> Procesar Documentos </a>    
      <a href="{{ route('monitoreo.empresas.create') }}" class="btn btn-primary pull-right btn-flat"> <span class="fa fa-plus"></span> Nueva Empresa </a>    
    </div>
  </div>

  @component('components.table', [ 'id' => 'table-empresa' , 'url' => route('monitoreo.empresas.search')  , 'class_name' => 'sainfo-noicon size-9em', 'thead' => [  'Codigo', 'Razón Social' , 'Ruc' , 'Email', 'Telefono', 'Cant. Doc', 'Acciones'] ])
  @endcomponent

  @include('partials.modal_eliminate', ['url' => route('monitoreo.empresas.destroy' , 'XX') ])
@endslot  

@endview_data