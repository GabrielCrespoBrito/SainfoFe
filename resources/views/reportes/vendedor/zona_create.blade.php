@view_data([
'layout' => 'layouts.master' ,
'title' => 'Reporte de Vendedores - Clientes',
'titulo_pagina' => 'Reporte de Vendedores - Clientes',
'bread' => [ ['Reportes'] , ['Reporte de Vendedores - Clientes'] ],
'assets' => ['libs' => ['datatable','datepicker','select2'],'js' => [ 'helpers.js']]
])

@push('css')
<link rel="stylesheet" href="{{ asset('css/reportes/reporte_basico.css') }}" />
@endpush

@slot('contenido')

  @component('components.report_form.parent', [ 'route' => route('reportes.vendedor_cliente.report')  ])
    @slot('content')

    {{--  --}}
      @component('components.report_form.fieldset', ['nameField' => 'Vendedor y Zona'])
        @slot('content')
          <div class="col-md-6">
            <div class="form-group">
              <select name="vendedor_id"  class="form-control input-sm">
                <option value=""> -- TODOS -- </option>
                @foreach( $vendedores as $vendedor )
                <option value="{{ $vendedor->Vencodi }}">  {{ $vendedor->Vencodi }} - {{ $vendedor->vennomb }} </option>
                @endforeach
              </select>
            </div>
          </div>

        <div class="col-md-6">
            <div class="form-group">
              <select name="zona"  class="form-control input-sm">
                <option value=""> -- TODAS -- </option>
                @foreach( $zonas as $zona )
                <option value="{{ $zona->ZonCodi }}">  {{ $zona->ZonNomb }} </option>
                @endforeach
              </select>
            </div>
          </div>
        @endslot
      @endcomponent

      @component('components.report_form.fieldset', ['nameField' => 'Tipo de Reporte'])
        @slot('content')

          <div class="col-md-12">
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