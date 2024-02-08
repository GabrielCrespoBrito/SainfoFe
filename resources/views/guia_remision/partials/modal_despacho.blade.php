@php
    $isGuiaTransportista = $guia->isGuiaTransportista();
@endphp

<div class="modal modal-seleccion fade" data-transportista="{{ (int) $isGuiaTransportista }}" id="modalDespacho">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Despacho guia</h4>
            </div>
            <div class="modal-body">
                <form id="form_despacho">
                    <div class="row">
                        @if ($isGuiaTransportista)
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon"> Destinatario</span>
                                    <div class="fixed_position">
                                        @php
                                            $destinatario = $guia->destinatario;
                                        @endphp
                                        <select id="destinatario" data-text="{{ $destinatario->getNombre() }}"
                                            data-id="{{ $destinatario->getId() }}" name="destinatario"
                                            data-url="{{ route('clientes.ventas.search') }}"
                                            class="form-control input-sm select2"
                                            style="display:none;position:absolute;">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Dirección Partida</span>
                                <input class="form-control input-sm" name="direccion_partida" type="text"
                                    value="{{ $guia->guidirp ? $guia->guidirp : optional($guia->almacen)->LocDire }}">
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Ubigeo Partida</span>
                                <div class="fixed_position">
                                    @php
                                        $mod_traslado = $guia->getModalidadTraslado();
                                        $ubigeo_partida = $guia->ubigeoPartida();
                                        $ubinomb = is_null($ubigeo_partida) ? '' : $ubigeo_partida->ubicodi . ' - ' . $ubigeo_partida->ubinomb;
                                        $ubicodi = is_null($ubigeo_partida) ? '' : $ubigeo_partida->ubicodi;
                                    @endphp
                                    <select id="ubigeo_partida" data-text="{{ $ubinomb }}"
                                        data-id="{{ $ubicodi }}" name="ubigeo_partida"
                                        data-url="{{ route('clientes.ubigeosearch') }}"
                                        class="form-control input-sm select2" style="display:none;position:absolute;">
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Dirección Llegada</span>
                                <input class="form-control input-sm" name="direccion_llegada" type="text"
                                    value="{{ $guia->guidill ?? $guia->cliente->PCDire }}">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Ubigeo Llegada</span>
                                <div class="fixed_position">
                                    @php
                                        $ubigeo_llegada = $guia->ubigeoLlegada();
                                        $ubinomb = is_null($ubigeo_llegada) ? '' : $ubigeo_llegada->ubicodi . ' - ' . $ubigeo_llegada->ubinomb;
                                        $ubicodi = is_null($ubigeo_llegada) ? '' : $ubigeo_llegada->ubicodi;
                                    @endphp
                                    <select id="ubigeo" data-text="{{ $ubinomb }}"
                                        data-id="{{ $ubicodi }}" name="ubigeo_llegada"
                                        data-url="{{ route('clientes.ubigeosearch') }}"
                                        class="form-control input-sm select2"" style="display:none;position:absolute;">
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if (!$isGuiaTransportista)

                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Motivo Traslado</span>
                                    <select class="form-control input-sm" data-old_option="" name="motivo_traslado">
                                        @foreach (cacheHelper('motivotraslado.all') as $motivo)
                                            <option data-type="{{ (int) $motivo->isProveedor() }}"
                                                {{ $motivo->MotCodi == $guia->motcodi ? 'selected' : '' }}
                                                value="{{ $motivo->MotCodi }}"> {{ $motivo->MotNomb }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

{{--  Motivo Traslado --}}

          <div class="form-group col-md-12">  
            <div class="input-group">
              <span class="input-group-addon">Modalida de Traslado</span>
                <select class="form-control input-sm" name="modalidad_traslado">
                  <option  value="01" {{ $mod_traslado ==  "01" ? 'selected=selected' : ''}}> Transporte Publico </option>
                  <option  value="02" {{ $mod_traslado ==  "02" ? 'selected=selected' : ''}}> Transporte Privado </option>
              </select>
            </div>
          </div>
          
{{--  --}}
                            {{--  --}}
                            @php
                                $exportData = $guia->getExportData();
                            @endphp

                            <div class="col-md-12 campos-export">

                                <div class="form-group col-md-6 no-p">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tipo Doc Export.</span>
                                        <select class="form-control input-sm" name="tipo_export">
                                            <option value="50"
                                                {{ $exportData->tipo_export == '50' ? 'selected=selected' : '' }}> Declaración
                                                Aduanera de Mercancías remitente, transportista </option>
                                            <option value="52"
                                                {{ $exportData->tipo_export == '52' ? 'selected=selected' : '' }}> Declaración
                                                Simplificada (DS) remitente, transportista </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon no-p">Serie</span>
                                        <input class="form-control input-sm text-uppercase" name="serie_doc_num"
                                            type="text" value="{{ $exportData->serie_doc_num }}">
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon no-p">Número</span>
                                        <input class="form-control input-sm text-uppercase" name="export_doc_num"
                                            type="text" value="{{ $exportData->export_doc_num }}">
                                    </div>
                                </div>

                            </div>
                            {{-- 
  50	Declaración Aduanera de Mercancías	remitente, transportista 
  52	Declaración Simplificada (DS)	remitente, transportista
--}}


                            {{--  --}}



                        @endif

                        <div data-field-traslado="02" class="mod-campo form-group col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Transportista</span>
                                @php
                                    $transportista = $guia->transportista;
                                    $transportistaNombre = optional($transportista)->descripcionComplete();
                                    $transportistaId = optional($transportista)->id;
                                @endphp
                                <select data-event="select2" placeholder="Elegir transportista"
                                    data-minimuminputlength="0" class="form-control input-sm select2"
                                    data-url="{{ route('transportista.search') }}"
                                    data-text="{{ $transportistaNombre }}" data-id="{{ $transportistaId }}"
                                    name="transportista" style="display:none;position:absolute">
                                </select>
                                <span class="input-group-addon" style="margin-bottom: 0">
                                    <a href="{{ route('transportista.create') }}" target="_blank" class=""
                                        id="notCredito">
                                        <span class="fa fa-plus"></span>
                                    </a>
                                </span>
                            </div>
                        </div>

                        {{--  --}}
                        <div data-field-traslado="02" class="mod-campo form-group col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Vehiculo</span>
                                @php
                                    $vehiculo = $guia->vehiculo;
                                    $vehiculoId = optional($vehiculo)->id;
                                    $vehiculoNombre = optional($vehiculo)->descripcionComplete();
                                @endphp
                                <select data-event="select2" placeholder="Elegir Elegir Vehiculo"
                                    data-minimuminputlength="0" class="form-control input-sm select2"
                                    data-url="{{ route('vehiculo.search') }}" data-text="{{ $vehiculoNombre }}"
                                    data-id="{{ $vehiculoId }}" name="placa"
                                    style="display:none;position:absolute">
                                </select>
                                <span class="input-group-addon" style="margin-bottom: 0">
                                    <a href="{{ route('vehiculo.create') }}" target="_blank" class=""
                                        id="notCredito">
                                        <span class="fa fa-plus"></span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        {{--  --}}

                        {{--  --}}
                        <div data-field-traslado="01" class="mod-campo form-group col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Empresa de Transporte</span>
                                @php
                                    $empresaTransporte = $guia->empresaTransporte;
                                    $empresaTransporteId = optional($empresaTransporte)->id;
                                    $empresaTransporteNombre = optional($empresaTransporte)->descripcionComplete();
                                @endphp
                                <select data-event="select2" placeholder="Elegir Empresa de transporte"
                                    data-minimuminputlength="0" class="form-control input-sm select2"
                                    data-url="{{ route('empresa_transporte.search') }}"
                                    data-text="{{ $empresaTransporteNombre }}" data-id="{{ $empresaTransporteId }}"
                                    name="empresa" style="display:none;position:absolute">
                                </select>
                                <span class="input-group-addon" style="margin-bottom: 0">
                                    <a href="{{ route('empresa_transporte.create') }}" target="_blank"
                                        class="" id="notCredito">
                                        <span class="fa fa-plus"></span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        {{--  --}}

                        <div class="form-group col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">Doc. Referencia</span>
                                <input class="form-control input-sm" name="documento_referencia" disabled
                                    type="text" value="{{ $guia->docrefe }}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"> Peso total</span>
                                <input class="form-control input-sm" name="peso_total" type="text" min="0"
                                    value="{{ $guia->guiporp ?? 1 }}">
                            </div>
                        </div>
                        @if (isset($serie_documento))
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Serie</span>
                                    <select name="serie_documento" class="form-control input-sm">
                                        @foreach ($tipos_documentos as $tipo_documento)
                                            <option {{ $loop->first ? 'selected' : '' }}
                                                data-codigo="{{ $tipo_documento['nuevo_codigo'] }}"
                                                value="{{ $tipo_documento['id'] }}"> {{ $tipo_documento['nombre'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-addon nro_domento">
                                        {{ $tipos_documentos[0]['nuevo_codigo'] }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if ($guia->isIngreso())
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"> Serie </span>
                                    <input class="form-control input-sm" name="serie_documento" type="text"
                                        value="{{ $guia->GuiSeri }}">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"> Número </span>
                                    <input class="form-control input-sm" name="numero_documento" type="text"
                                        value="{{ $guia->GuiNumee }}">
                                </div>
                            </div>
                        @endif
                </form>
            </div>
            @isset($show)
                <div class="row">
                    <div class="col-md-12">
                        @if (!$show || !$guia->isCerrada())
                            <button type="button" class="btn btn-success save_despacho"> Guardar </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                Cancelar </button>
                            <label class="pull-right" for="imprimir_guia"> <input id="imprimir_guia" type="checkbox"
                                    name="imprimir"> Imprimir </label>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>
    </div>
