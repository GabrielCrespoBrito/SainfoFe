@view_data([
    'layout' => 'layouts.master',
    'title' => 'Reporte de Cotizaciones',
    'titulo_pagina' => 'Reporte de Cotizaciones',
    'bread' => [['Reportes'], ['Reporte de Cotizaciones']],
    'assets' => ['libs' => ['datatable', 'datepicker', 'select2'], 'js' => ['helpers.js', 'reportes/venta_pago.js']]
])

    @push('css')
        <link rel="stylesheet" href="{{ asset('css/reportes/reporte_basico.css') }}" />
    @endpush

    @slot('contenido')

        @component('components.report_form.parent', ['route' => route('coti.report-post')])
            @slot('content')
                {{--  --}}
                @component('components.report_form.fieldset', ['nameField' => 'Vendedor / Usuario / Estado / Zonas'])
                    @slot('content')
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="vencodi" class="form-control input-sm">
                                    <option value=""> -- TODOS -- </option>
                                    @foreach ($vendedores as $vendedor)
                                        <option value="{{ $vendedor->Vencodi }}"> {{ $vendedor->Vencodi }} - {{ $vendedor->vennomb }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="usucodi" class="form-control input-sm">
                                    <option value=""> -- TODOS -- </option>
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->usucodi }}"> {{ $usuario->usulogi }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="estado" class="form-control input-sm">
                                    <option value=""> -- TODAS -- </option>
                                    <option value="P"> PENDIENTES </option>
                                    <option value="L"> LIBERADA </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="zona" class="form-control input-sm">
                                    <option value=""> -- TODAS -- </option>
                                    @foreach ($zonas as $zona)
                                        <option value="{{ $zona->ZonCodi }}"> {{ $zona->ZonNomb }} </option>
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
                            <input type="text" value="{{ $fechas->inicio }}" name="fecha_desde"
                                class="form-control input-sm datepicker no_br flat text-center">
                        </div>
                        <div class="col-md-6">
                            <input type="text" value="{{ $fechas->final }}" name="fecha_hasta"
                                class="form-control input-sm datepicker no_br flat text-center">
                        </div>
                    @endslot
                @endcomponent



                @component('components.report_form.fieldset', ['nameField' => 'Tipo de Reporte'])
                    @slot('content')
                        <div class="col-md-12">
                            <div class="form-group">
                                <select name="tipo_reporte" class="form-control input-sm">
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
