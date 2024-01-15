@component('pdf.documents.partials.content', [
    'class_name' => 'a4',
    'logoMarcaAgua' => null,
    'preliminar' => !$venta2->exists,
    'logoMarcaAguaSizes' => null,
])

    @slot('content')
        {{-- HEADER --}}
        @component('pdf.documents.partials.header')
            @slot('content')
                <div class="row">

                    @include('pdf.documents.partials.logo', [
                        'class_name' => 'col-7 c-white',
                    ])

                    @include('pdf.documents.partials.id', [
                        'class_name' =>
                            'col-3 border-style-solid border-radius-5 border-width-1 border-color-gray m-0 h3 bg-gray-800 text-center',
                        'ruc' => $empresa['EmpLin1'],
                        'class_ruc' => ' bold pt-x6 ',
                        'class_nombre' => 'bold h3 pt-0 pl-2 pr-2 ',
                        'class_numeracion' => 'bold pb-x6',
                    ])

                </div>

                <div class="row">

                    @include('pdf.documents.partials.info_empresa', [
                        'class_name' => 'col-12',
                        'direccion_div_class' => 'bold',
                        'correo_text_class' => 'bold',
                        'correo_campo_nombre' => $cliente_correo,
                        'telefonos_text_class' => 'bold',
                        'telefonos_campo_nombre' => 'Telefonos:',
                        'telefonos' => $telefonos,
                        'direccion' => $direccion,
                    ])

                    @if ($logoSubtitulo)
                        @include('pdf.documents.partials.logo_subtitulo', ['class_name' => 'col-3'])
                    @endif

                </div>



                <div class="row mb-x10" style="margin-top:5px; margin-bottom: 10px">

                    @include('pdf.documents.partials.info_cliente', [
                        'class_name' =>
                            'col-6 border-width-1 py-x10 px-x10 border-color-gray border-style-solid border-radius-5  min-h-150',
                        'nombre_campo_nombre' => 'DENOMINACIÓN:',
                        'nombre_campo_class' => 'bold ',
                        'nombre' => $cliente->PCNomb,
                        'documento_campo_nombre' => $cliente->getNombreTipoDocumento() . ':',
                        'documento_campo_class' => 'bold ',
                        'documento' => $cliente->PCRucc,
                        'direccion_campo_nombre' => 'DIRECCIÓN',
                        'direccion_campo_class' => 'bold ',
                        'direccion' => $cliente->PCDire,
                        'vendedor' => false,
                    ])


    {{-- @dd($placa) --}}

                    @include('pdf.documents.partials.info_documento3', [
                        'class_name' =>
                        'col-4 border-width-1 py-x10 px-x10 border-color-gray border-style-solid border-radius-5 min-h-150',
                        'showPagos' => false,
                    ])

                </div>
            @endslot
        @endcomponent

        {{-- /HEADER --}}

        @php
            $cant_items = $items->count();
        @endphp

        @include('pdf.documents.partials.table', [
            'class_name' => 'mb-x20',
            'class_name_table' => 'col-10 odd-background',
            'thead_class' => 'py-x4 bg-gray-800-i c-black bold border-solid border-solid-1 border-width-1',
            'thead_importe_class' => '',
            'tbody_class' => 'pt-x3 pl-x3 pb-x2 font-size-9',
            'class_precio_unit' => 'py-x2 text-right pr-x3',
            'class_importe' => 'py-x2  text-right pr-x3 ',
            'class_cant' => 'py-x2 text-right pl-x1 pr-x8 pr-x3',
            'class_orden' => 'py-x2 text-center',
            'class_codigo' => 'py-x2 text-center',
            'class_unidad' => 'py-x2 text-center',
            'class_descripcion' => 'py-x2 ',
        ])

        <p>-</p>
        {{-- /FOOTER  --}}

        <div class="row">

            <div class="col-7"></div>

            <div class="col-3">
                @include('pdf.documents.partials.totales', [
                    'class_name' => 'col-10 text-right',
                    'class_name_table' => 'text-right',
                    'total_nombre_class' => 'bold pl-x5 text-right',
                    'total_value_class' => 'text-right pr-x5',
                    'info_text_consultada_class' => 'bold',
                ])
            </div>

        </div>

        <div class="row" style="margin-top:10px">
            @include('pdf.documents.partials.info_anexo', [
                'class_name' =>
                    'col-12 p-x5 pl-x10 border-style-solid border-radius-10 border-color-gray border-width-1',
            ])
        </div>

        <div class="row" style="margin-top:10px">

            <div class="col-5">

                @include('pdf.documents.partials.cuentas', [
                    'class_name' => 'col-10',
                    'class_name_table' => '',
                    'titulo_div_class' => 'bold pl-x3 mb-x3 ',
                    'cuenta_text_class' => 'pl-x10 ',
                    'cuenta_cuenta_text_class' => 'bold',
                ])


                <div class="col-10">
                    <span class="bold pl-x3 mb-x3"> FORMA DE PAGO: </span>
                    <div class="pl-x10"> {{ $forma_pago->connomb }} [ {{ $medio_pago_nombre }} ]
                    </div>
                </div>

            </div>


            <div class="col-5 text-right">
                {{--  --}}
                @include('pdf.documents.partials.pagos', [
                    'class_name' => 'col-10 text-right',
                    'class_name_table' => '',
                    'titulo_div_class' => 'bold pl-x3 text-uppercase bold pl-x3 mb-x3 ',
                    'tr_titulo_class' => 'bold text-right',
                    'tr_valor_class' => 'text-right',
                    'textAlign' => 'right',
                ])
                {{--  --}}

            </div>

        </div>



        @if (!$venta2->isNotaVenta())
            <div class="row border-style-solid border-radius-10 border-color-gray border-width-1" style="margin-top:10px">

                <div class="col-7 p-x10">

                    {{-- <div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_nombre {{ $info_nombre_class }}">Resumen</span>: 
  <span class="info_text {{ $info_text_class  }}"> {{ $hash }}= </span>
</div> --}}


                    <div class="info_text_div">

                        Resumen : <span class="bold"> {{ $venta2->firma }} </span> /
                        Hora: <span class="bold">{{ $venta2->VtaHora }} </span> /
                        Peso: <span class="bold"> {{ decimal($venta2->getPesoTotal()) }} </span>
                    </div>

                    <div class="info_text_div"> Representaciòn Impresa de: <span class="bold">{{ $nombre_documento }} </span>
                    </div>

                    <div class="info_text_div"> Esta puede ser consultada en: <span
                            class="bold">{{ removeHttp(config('app.url_busqueda_documentos')) }} </span> </div>

                    {{--  --}}
                    {{-- @include('pdf.documents.partials.info_adicional', [
      'class_name' => 'col-8 mb-x3',
      'class_qr_div' => 'col-2',
      'is_nota_venta'=> $venta2->isNotaVenta(),
      'info_adicional_class' => $venta2->isNotaVenta() ? "col-10 pl-x5 " :  "col-7 ",
      'info_nombre_class'=> 'bold',
      'info_text_class'=> '',
      'hash' => $firma ,
      'hora' => $venta2->VtaHora ,
      'peso' => decimal($venta2->getPesoTotal()),
      'nombreDocumento' => $nombre_documento,
      'pageDocumento' => removeHttp(config('app.url_busqueda_documentos')),
      ]) --}}
                    {{--  --}}
                </div>

                <div class="col-3 text-right">
                    <div class="qr_div pull-right">
                        <img style="padding: 0;margin: 0;z-index:-1000" src="data:image/png;base64, {!! base64_encode($qr) !!} ">
                    </div>
                </div>

            </div>
        @endif



        {{-- /FOOTER  93DWf5E!9$v9& --}}
    @endslot
@endcomponent
