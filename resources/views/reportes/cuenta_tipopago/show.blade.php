@view_data([
'layout' => 'layouts.master' ,
'title' => 'Venta por Tipos de Pago',
'titulo_pagina' => 'Venta por Tipos de Pago',
'bread' => [ ['Reportes'] , ['Venta por Tipos de Pago'] ],
'assets' => ['libs' => ['datatable','datepicker','select2'],'js' => [ 'helpers.js','reportes/venta_pago.js']]
])

@push('css')
<link rel="stylesheet" href="{{ asset('css/reportes/reporte_basico.css') }}" />
@endpush

@slot('contenido')

@include('partials.errors_html')


<form target="_blank" action="{{ route('reportes.ventas_tipopago.pdf') }}" method="post">

  @CSRF

  <div class="reportes">

    <div class="filtros">

      <!-- Condicion de venta -->
      <div class="filtro" id="condicion">
        <div class="cold-md-12">
          <fieldset class="fsStyle">
            <legend class="legendStyle">Tipo de Pago </legend>
            <div class="row" id="demo">
              <div class="col-md-12">
                <div class="form-group">
                  <select name="tipo_pago_id" id="tipo_pago" class="form-control">
                    <option value=""> -- TODOS -- </option>

                    @foreach( $tipos_pagos as $tipo_pago )
                    <option {{ old('tipo_pago_id') == $tipo_pago->TpgCodi ? 'selected' : ''  }} value="{{ $tipo_pago->TpgCodi }}"> {{ $tipo_pago->TpgNomb }} </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
      <!-- Condicion de venta -->

      <!-- Articulo -->
      <div class="filtro" id="condicion">
        <div class="cold-md-12">
          <fieldset class="fsStyle">
            <legend class="legendStyle">Cliente </legend>
            <div class="row" id="demo">
              <div class="col-md-12">
                <div class="fixed_position">
                  <select id="cliente_documento" data-settings="{{ json_encode([ 'allowClear' => true , 'placeholder' => 'Buscar Cliente' , 'theme' => 'default container-cliente-search' ]) }}" data-url="{{ route('clientes.ventas.search') }}" name="cliente_documento" class="select2 form-control input-sm" style="position:absolute;"></select>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
      <!-- Articulo -->

      <!-- Articulo -->
      <div class="filtro" id="condicion">
        <div class="cold-md-12">
          <fieldset class="fsStyle">
            <legend class="legendStyle">Fechas (desde,hasta)</legend>

            <div class="row" id="demo">

              <div class="col-md-6">
                <?php $date = old('fecha_desde', date('Y-m-d')); ?>
                <input type="text" value="{{ $date }}" name="fecha_desde" class="form-control input-sm datepicker no_br flat text-center">

              </div>

              <div class="col-md-6">
                <?php $date = old('fecha_hasta', date('Y-m-d')); ?>
                <input type="text" value="{{ $date }}" name="fecha_hasta" class="form-control input-sm datepicker no_br flat text-center">
              </div>

            </div>

          </fieldset>
        </div>
      </div>
      <!-- Articulo -->


    </div>
  </div>


  <div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary btn-flat"> Generar </button>
    </div>
  </div>


</form>


@endslot


@endview_data