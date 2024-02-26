@component('pdf.documents.partials.content', [
    'class_name' => 'a4',
    'logoMarcaAgua' => $logoMarcaAgua ?? null,
    'logoMarcaAguaSizes' => $logoMarcaAguaSizes ?? null,
])

    @slot('content')

        {{-- HEADER --}}
        @component('pdf.documents.partials.header')
            @slot('content')
                <div class="row">

                    <div class="col-7">


                        @include('pdf.documents.partials.logo', [
                            'class_name' => ' c-white',
                        ])

                        @include('pdf.documents.partials.info_empresa', [
                            'class_name' => 'letter-spacing-1px',
                            'direccion_div_class' => 'bold',
                            'rubro_text_class' => 'text-center bold font-size-1.1',
                            'rubro' => $rubro,
                            'correo_div_class' => 'text-center',
                            'correo_text_class' => 'text-center',
                            'telefonos_div_class' => 'bold text-center',
                            'telefonos' => $telefonos,
                            'direccion_text_class' => 'bold text-uppercase text-center',
                            'direccion' => $direccion,
                        ])

                    </div>



                    {{-- / --}}
                    <div class="col-3">

                        @include('pdf.documents.partials.id', [
                            'class_name' =>
                                ' border-style-solid border-radius-10 border-width-2 m-0 h4 text-center border-box',
                            'ruc' => $empresa['EmpLin1'],
                            'class_nombre' =>
                                'pt-x5 pb-x6 mb-x2 box-sizing-border-box font-size-9 bg-gray-800 bg-blue-light-100',
                            'class_ruc' => ' pt-x6 pb-x3',
                            'class_numeracion' => ' pt-x3 pb-x6',
                        ])

                        @if ($logoSubtitulo)
                            @include('pdf.documents.partials.logo_subtitulo', [
                                'class_name' => 'col-10',
                                'img_logo_class' => 'outline-none',
                            ])
                        @endif

                    </div>


                </div>



                <div class="row">

                    @include('pdf.documents.partials.info_cliente3', [
                        'class_name' =>
                            'col-12 p-x8 border-width-2 mt-x5 mb-x5 border-style-solid border-radius-10 letter-spacing-1px',
                        'nombre_text_class' => 'bold ',
                        'nombre' => $cliente->PCNomb,
                        'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
                        'documento_campo_class' => '',
                        'documento' => $cliente->PCRucc,
                        'direccion_campo_nombre' => 'Dirección',
                        'direccion_campo_class' => '',
                        'direccion' => $cliente->PCDire,
                        'fecha_emision' => $fecha_emision_,
                        'vendedor' => false,
                    ])

                </div>
            @endslot
        @endcomponent

        {{-- /HEADER --}}


        @php
            $cant_items = $items->count();
            $footerBreak = ($cant_items >= 20 && $cant_items <= 45) || ($cant_items >= 88 && $cant_items <= 105) || $cant_items >= 131;
            $classFooter = '';
        @endphp


        {{-- <div class="col-10 p-x2 letter-spacing-1px border-color-black border-style-solid border-width-2 border-radius-10 overflow-hidden  '  . ($footerBreak ? '' : 'container-table-height')"> --}}

        <div
            class="col-10 letter-spacing-1px border-color-black border-style-solid border-width-2 border-radius-10 overflow-hidden">

            {{-- @include('pdf.documents.partials.table', [
                'complete_tds_spaces' => false,
                'cantAddNV' => 30,
                'cantAddDoc' => 20,
                'class_name' => '',
                'class_name_table' => 'border-collapse-separate',
                'thead_class' =>
                    'font-size-9 bg-blue-light-100  bold c-black pb-x10 pt-x10 pl-x3 border-color-black  border-bottom-style-solid border-width-2',
                'tbody_class' => 'pt-x2 pb-x2 pl-x3 pb-x2',
                'class_precio_unit' => 'text-right pr-x3',
                'class_importe' => 'text-right pr-x3 ',
                'class_cant' => 'text-right pl-x1 pr-x8 pr-x3',
                'class_orden' => 'text-center',
                'class_codigo' => 'text-center',
                'class_unidad' => 'text-center',
                'class_descripcion' => '',
            ]) --}}

            @include('pdf.documents.partials.table_cot1', [
                'completar_tds' => true,
                'cant_items_add' => 20,
                'class_name' => 'col-10 border-color-blue-light',
                'class_name_table' =>
                    'col-10 border-collapse-separate',
                'thead_class' =>
                    'font-size-9 bg-blue-light-100  bold c-black pb-x10 pt-x10 pl-x3 border-color-black  border-bottom-style-solid border-width-2',
                'tbody_class' =>
                    'pt-x2 pb-x2 pl-x3 pb-x2',
                'class_precio_unit' =>
                    'text-right  pr-x3',
                'class_importe' => 'text-right pr-x3 ',
                'class_cant' =>
                    'text-right pl-x1 pr-x8 pr-x3',
                'class_orden' => 'text-center',
            ])

            <div
                class="col-10 letter-spacing-1px pl-x10 pr-x10 pt-x3 pb-x3 border-color-black border-top-style-solid border-width-2 overflow-hidden">
                <table width="100%">
                    <tr style="vertical-align: middle">
                        <td style="vertical-align: middle"
                            class="letter-spacing-1px border-color-black border-right-style-solid border-width-2" width="50%">
                            <span class="text-uppercase text-center font-size-15px"> Son: {{ $cifra_letra }} </span>
                        </td>

                        <td width="50%">

                            {{-- Totales  --}}

                            {{-- @include('pdf.documents.partials.totales', [
                                'class_name' => 'table-totales',
                                'style' => 'bottom:0;right:0',
                                'showACuenta' => false,
                                'class_name_table' => 'border-width-1 table-with-border border-radius-5',
                                'total_nombre_class' => 'pl-x5',
                                'total_value_class' => 'text-right pr-x5',
                                'info_text_consultada_class' => '',
                            ]) --}}

                            @include('pdf.documents.partials.cuentas2', [
                                'class_name' => 'col-10 ',
                                'class_name_table' => '',
                                'titulo_div_class' => 'bold pl-x3 mb-x3',
                                'cuenta_text_class' => 'pl-x3',
                                'con_cuentas' => false,
                                'border' => false,
                                'titulo' => 'CUENTAS BANCARIAS',
                                'cuenta_cuenta_text_class' => 'bold',
                            ])



                            {{-- /Totales --}}
                        </td>


                    </tr>
                </table>
            </div>


        </div>


        {{--  --}}

        <div
            class="col-10 mt-x5 letter-spacing-1px pl-x10 pr-x10 pt-x3 pb-x3 border-color-black border-radius-10 border-style-solid border-width-2 overflow-hidden">

            <table width="100%">
                <tr style="vertical-align: top">

                    <td style="vertical-align: top"
                        class="letter-spacing-1px border-color-black border-right-style-solid border-width-2" width="50%">

                        <div class="bg-blue-light-100 text-center font-size-15px bold"> Condiciones </div>

                        {{--  --}}
                        <div class="row">
                            {{-- @include('pdf.documents.partials.info_anexo', [
                                'cifra_letra' => false,
                                'class_name' => 'col-10',
                            ]) --}}

                            @include('pdf.documents.partials.condiciones_cot1', [
                                'class_name' => 'col-10 ',
                                'class_name_table' => '',
                                'titulo_div_class' =>
                                    'bold pl-x3 mb-x3 border-color-blue-light border-bottom-style-solid border-width-1',
                                'cuenta_text_class' => 'pl-x3',
                                'titulo' => '',
                                'cuenta_cuenta_text_class' => 'bold',
                            ])

                        </div>
                        {{--  --}}

                    </td>

                    {{-- Pagos  --}}
                    <td width="50%" style="overflow:hidden">

                        <div class="bg-blue-light-100 text-center font-size-15px bold"> Cuentas Bancarias </div>

                        <div>

                            @include('pdf.documents.partials.cuentas', [
                                'class_name' => 'col-10 pt-x5',
                                'titulo' => false,
                                'class_name_table' => '',
                                'titulo_div_class' => 'bold pl-x3 mb-x3 border-bottom-style-solid border-width-1',
                                'cuenta_text_class' => 'pl-x3',
                                'cuenta_cuenta_text_class' => 'bold',
                            ])


                        </div>

                    </td>
                    {{-- /Pagos --}}

                </tr>
            </table>
        </div>


        {{-- Start --}}

        {{--  --}}

        <div
            class="col-10 mt-x5 letter-spacing-1px pl-x10 pr-x10 pt-x3 pb-x3 border-color-black border-radius-10 border-style-solid border-width-2 overflow-hidden">

            @include('pdf.documents.partials.coti_despedida', [
                'class_name' => 'col-12',
                'descripcion_class' => 'text-center',
                'receptor_class' => 'text-center mt-x30 mb-x1 pt-x10',
                'receptor_text_class' => 'text-underline',
                'show_peso' => true,
            ])


        </div>


        {{-- End --}}
    @endslot
@endcomponent
