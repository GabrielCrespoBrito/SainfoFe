@view_data([
  'layout' => 'layouts.master_admin' , 
  'title'  => 'Registro de Ventas Por Periodo',
  'titulo_pagina'  => 'Registro de Ventas Por Periodo', 
  'bread'  => [ ['Registro de Ventas Por Periodo'] ],
  'assets' => ['libs' => ['datatable', 'datepicker'],'js' => ['helpers.js','reportes/ventas_mensual.js']]
])

@slot('contenido')

<div class="reportes">
  @include('reportes.ventas_mensual.partials.filtro')
</div>


<div class="reporte-data">
</div>


@endslot  


@endview_data