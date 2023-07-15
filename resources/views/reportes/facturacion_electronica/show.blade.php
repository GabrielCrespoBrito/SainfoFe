@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Reporte Facturación electronicica',
  'titulo_pagina'  => 'Reporte Facturación electronicica', 
  'bread'  => [ ['Reporte facturación electronica', '#' ]],
  'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','reportes/facturacion_electronica.js']]
])

@slot('contenido')

  @include('reportes.facturacion_electronica.partials.form')
  @include('reportes.facturacion_electronica.partials.table')

@endslot  

@endview_data