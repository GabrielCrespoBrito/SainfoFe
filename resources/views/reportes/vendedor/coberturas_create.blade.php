@view_data([
'layout' => 'layouts.master' ,
'title' => 'Reporte de Vendedores - Coberturas',
'titulo_pagina' => 'Reporte de Vendedores - Coberturas',
'bread' => [ ['Reportes'] , ['Reporte de Vendedores - Coberturas'] ],
'assets' => ['libs' => ['datatable','datepicker','select2'],'js' => [ 'helpers.js','reportes/venta_pago.js']]
])

@push('css')
<link rel="stylesheet" href="{{ asset('css/reportes/reporte_basico.css') }}" />
@endpush

@slot('contenido')

  @component('components.report_form.parent', [ 'route' => route('reportes.vendedor_cobertura.report')  ])
    @slot('content')

    {{--  --}}
      @component('components.report_form.fieldset', ['nameField' => 'Vendedor - Local - Marca'])
        @slot('content')
          <div class="col-md-4">
            <div class="form-group">
              <select name="vendedor_id"  class="form-control input-sm">
                <option value=""> -- TODOS -- </option>
                @foreach( $vendedores as $vendedor )
                <option value="{{ $vendedor->Vencodi }}">  {{ $vendedor->Vencodi }} - {{ $vendedor->vennomb }} </option>
                @endforeach
              </select>
            </div>
          </div>

        <div class="col-md-4">
            <div class="form-group">
              <select name="local_id"  class="form-control input-sm">
                <option value=""> -- TODOS -- </option>
                @foreach( $locales as $local )
                <option value="{{ $local->LocCodi }}">  {{ $local->LocNomb }} - {{ $local->LocDire }} </option>
                @endforeach
              </select>
            </div>
          </div>

        <div class="col-md-4">
            <div class="form-group">
              <select name="marca_id"  class="form-control input-sm">
                <option value=""> -- TODOS -- </option>
                @foreach( $marcas as $marca )
                <option value="{{ $marca->MarCodi }}">  {{ $marca->MarNomb }} </option>
                @endforeach
              </select>
            </div>
          </div>

        @endslot
      @endcomponent

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

      @component('components.report_form.fieldset', ['nameField' => 'Clientes y Tipo de Reporte'])
        @slot('content')
          <div class="col-md-6">
            <div class="fixed_position">
              <select id="cliente_documento" data-settings="{{ json_encode([ 'allowClear' => true , 'placeholder' => 'Buscar Cliente' , 'theme' => 'default container-cliente-search' ]) }}" data-url="{{ route('clientes.ventas.search') }}" name="cliente_id" class="select2 form-control input-sm" style="position:absolute;"></select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <select name="tipo_reporte"  class="form-control input-sm">
                <option value="0"> PDF </option>
                <option value="1"> Excell </option>
              </select>
            </div>
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