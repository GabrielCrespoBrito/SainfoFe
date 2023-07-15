@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Reporte Guia electronicica',
  'titulo_pagina'  => 'Reporte Guia electronicica', 
  'bread'  => [ ['Reporte Guia electronica', '#' ]],
  'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','reportes/guia_electronica.js']]
])

@slot('contenido')

  @include('reportes.guia_electronica.partials.form')
  @include('reportes.guia_electronica.partials.table')

@endslot  

@endview_data