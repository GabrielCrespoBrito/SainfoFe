@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Ordenes',
  'titulo_pagina'  => 'Ordenes', 
  'bread'  => [ ['Tienda'], ['Ordenes'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','tienda/orden/index.js']]
])

@slot('contenido')

  @include('tienda.orden.partials.filters')

  @component('components.table', [ 'id' => 'tableOrden' , 'url' => route('tienda.orden.search')  , 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ '#' , 'Fecha' , 'Cant.Iems' , 'Cliente' , 'Ruc/Dni', 'Email' ,'Telf', 'Estatus' ,''] ])
  @endcomponent

@endslot  

@endview_data


