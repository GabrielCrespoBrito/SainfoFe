@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Reporte Detracción',
  'titulo_pagina'  => 'Reporte Detracción', 
  'bread'  => [ ['Reportes'] , ['Detracción'] ],
  'assets' => ['libs' => ['datepicker'],'js' => ['helpers.js','compras/crud_mod.js' ]]
])

@slot('contenido')
  @include('reportes.detraccion.partials.form')
  @includeWhen($show_report, 'reportes.detraccion.partials.table', [ 'data_report' => $data_report ])
@endslot  

@endview_data