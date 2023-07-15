@view_data([
'layout' => 'layouts.master' ,
'title' => 'Reporte Mejores Vendedores ',
'titulo_pagina' => 'Reporte Mejores Vendedores',
'bread' => [ ['Reportes'] , ['Reporte Mejores Vendedores'] ],
'assets' => ['libs' => ['datatable','datepicker','select2'],'js' => [ 'helpers.js','reportes/venta_pago.js']]
])

@push('css')
<link rel="stylesheet" href="{{ asset('css/reportes/reporte_basico.css') }}" />
@endpush

@slot('contenido')

  @component('components.report_form.parent', [ 'route' => route('reportes.vendedor_estadistica.report')  ])
    @slot('content')
     @php
        $fechas = fechas_reporte();
      @endphp

      @component('components.report_form.fieldset', ['nameField' => 'Fecha Desde / Hasta'])
        @slot('content')
          <div class="col-md-6">
          <input type="text" value="{{ $fechas->inicio }}" name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">
        </div>
        <div class="col-md-6">
          <input type="text" value="{{ $fechas->final }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">
        </div>
        @endslot
      @endcomponent

    @endslot
  @endcomponent

@endslot

@slot('js')
@include('partials.errores')
@endslot

@endview_data