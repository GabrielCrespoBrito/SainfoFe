@extends('layouts.master')

@section('bread')
    <li> Ventas </li>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatable/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/tagsinput/tagsinput.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/popover/style.css') }}" />
    <style>
        /* Woke station */
        #ui-datepicker-div {
            display: none;
        }
    </style>
@endsection

@section('js')
    <?php $class_adicional = 'venta_index'; ?>
    <script src="{{ asset('plugins/popover/script.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript"
        charset="utf-8"></script>
    <script src="{{ asset('plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/tagsinput/tagsinput.js') }}"></script>
    <script src="{{ asset('plugins/datatable/dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript">
        var active_or_disable_;
        var active_ordisable_tr;
        var table;
        var fecha_actual = '{{ date('Y-m-d') }}';
        var url_venta_consulta = "{{ route('ventas.consulta') }}";
        var url_eliminar_factura = "{{ route('ventas.eliminar') }}";
        var url_send_email = "{{ route('mail.redactado') }}";
        var url_anular_boleta = "{{ route('boletas.anular') }}";
        var url_pdf = "{{ route('ventas.imprimirFactura', ['XXX']) }}";
        var url_xml = "{{ route('ventas.ver_xml', ['XXX']) }}";
        var url_cdr = "{{ route('ventas.ver_cdr', ['XXX']) }}";
        var url_mails_enviados = "{{ route('mail.enviados') }}";
        var url_save_guiasalida = "{{ route('ventas.saveguia') }}"
    </script>
    <script src="{{ asset(mix('js/mix/helpers.js')) }}"></script>
    <script src="{{ asset('js/clientes/scripts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/print_js/ConectorPlugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ventas/print_ticket.js') }}"></script>
    {{-- <script src="{{ asset('js/ventas/index.js') }}"> </script> --}}
    <script src="{{ asset(mix('js/ventas/mix/index.js')) }}"></script>
    <script src="{{ asset('js/guia/modal_crear_guia.js') }}"></script>

@endsection

@section('titulo_pagina', 'Ventas')


@section('contenido')

    <div class="col-md-8 ventas">
        <div class="row">

            <div class="col-md-3 no_p">
                @include('components.btn_procesando')
                @php
                    $tipo = request()->request->get('tipo') ?? 'todos';
                    $tiposDocumentos = [
                        'todos' => 'Todos',
                        '01' => 'Factura',
                        '03' => 'Boleta',
                        '07' => 'Nota Credito',
                        '08' => 'Nota Debito',
                    ];

                    if (
                        auth()
                            ->user()
                            ->checkPermissionTo_(
                                concat_space(PermissionSeeder::A_VER_NOTAS_VENTA, PermissionSeeder::R_VENTA),
                            )
                    ) {
                        $tiposDocumentos['52'] = 'Nota Venta';
                    }
                @endphp


                {!! Form::select('tipo', $tiposDocumentos, $tipo, [
                    'class' => 'form-control input-sm',
                    'data-reloadtable' => 'table',
                ]) !!}

                <input type="hidden" name="status" value="{{ request()->request->get('status') }}">

            </div>

            <?php
            $buscar_por_fecha = 0;
            $fecha_desde = date('Y-m-d');
            $fecha_hasta = date('Y-m-d');
            
            if ($mes = request()->request->get('mes')) {
                $datesSearch = transformMesCodi($mes);
                $buscar_por_fecha = 1;
                $fecha_desde = $datesSearch[0];
                $fecha_hasta = $datesSearch[1];
            }
            // Reserved
            ?>

            <input type="hidden" value="{{ $buscar_por_fecha }}" name="buscar_por_fecha">

            <div class="col-md-2 no_p col-sm-6  col-xs-6">
                <input type="text" value="{{ $fecha_desde }}" name="fecha_desde"
                    class="form-control flat input-sm datepicker no_br text-center">
            </div>

            <div class="col-md-2 no_p col-sm-6  col-xs-6">
                <input type="text" value="{{ $fecha_hasta }}" name="fecha_hasta"
                    class="form-control flat input-sm datepicker no_br text-center">
            </div>

            <div class="col-md-1 no_p">
                <button data-toggle="tooltip" title="Buscar documentos por estas fechas"
                    class="btn btn-block buscar_factura_b btn-default btn-sm btn-flat"><span class="fa fa-calendar"></span>
                </button>
            </div>

            <div class="col-md-2 col-sm-6  col-xs-6 no_p">
                <select name="local" class="form-control input-sm">
                    @foreach ($locales as $local)
                        <option value="{{ $local->loccodi }}" {{ $local->defecto ? 'selected=selected' : '' }}>Local -
                            {{ optional($local->local)->LocNomb }} </option>
                    @endforeach
                </select>
            </div>

            @include('components.specific.select_estado_almacen', [
                'size' => 'col-md-2 col-sm-6 col-xs-6 no_p',
            ])

        </div>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-12 acciones-div ww no_p">

        <a href="{{ route('ventas.pendientes') }}" target="_blank" data-toggle="tooltip" title="Pendientes"
            class="btn btn-default btn-flat pull-right pendientes"> Pendientes </a>

        <a href="{{ route('ventas.nueva_factura') }}" data-toggle="tooltip" title="Nueva"
            class="btn btn-primary btn-flat pull-right crear-nuevo"> <span class="fa fa-plus"></span> Nueva </a>

    </div>

    @include('ventas.partials.table_index')

    @include('ventas.partials.modal_redactar_correo')

    {{-- data-backdrop="static" data-keyboard="false" --}}

    @component('components.modal', [
        'id' => 'modalNC',
        'idContent' => 'form-nc',
        'size' => 'modal-md',
        'title' => '',
        'backdrop' => 'static',
        'keyboard' => 'false',
    ])
        @slot('body')
        @endslot
    @endcomponent

    @component('components.modal', [
        'id' => 'modalND',
        'idContent' => 'form-nd',
        'size' => 'modal-md',
        'title' => '',
        'backdrop' => 'static',
        'keyboard' => 'false',
    ])
        @slot('body')
        @endslot
    @endcomponent


@endsection
