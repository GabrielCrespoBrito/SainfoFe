@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Reporte de compras',
  'titulo_pagina'  => 'Reporte de compras', 
  'assets' => ['libs' => ['datepicker'],'js' => ['helpers.js', 'reportes/compras.js' ]]
])

@slot('contenido')

  <div class="reportes">
    @include('compras.partials.reporte.form')
  </div>
@endslot  

@endview_data