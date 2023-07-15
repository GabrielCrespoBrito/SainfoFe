@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Inventario Valorizado',
  'titulo_pagina'  => 'Inventario Valorizado', 
  'bread'  => [ ['Reportes'] , ['Inventario Valorizado'] ],
  'assets' => ['libs' => ['datepicker'],'js' => ['helpers.js','compras/crud_mod.js' ]]
])

@slot('contenido')
  @include('reportes.inventario_valorizado.partials.form')
  @if($show_report)
    @include('reportes.inventario_valorizado.partials.table', [ 'data_report' => $data_report['data'], 'total_general' => $data_report['total_general'] ])
  @endif

@endslot  

@endview_data