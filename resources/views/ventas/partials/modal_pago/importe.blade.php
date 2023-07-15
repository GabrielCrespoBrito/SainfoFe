
@php
  $urlSearchNC =  $urlSearchNC ?? route('ventas.nota_credito.topay');
  $tiposPagos = $tiposPagos ?? cacheHelper('tipopago.all');
@endphp

<!-- facto datos -->
<div class="facto_datos">
  <div class="row">
    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Tipo pago</span>
        <select class="form-control input-sm"   data-name="TpgCodi" data-field="TpgCodi" name="tipopago">
          @foreach( $tiposPagos as $tipoPago )
          <option  data-bancario="{{ (int) $tipoPago->isBancario() }}"  value="{{ $tipoPago->TpgCodi }}">{{ $tipoPago->TpgNomb }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Fecha pago</span>
        <input class="form-control input-sm" disabled name="fecha_pago" value="{{ date('Y-m-d') }}">
      </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Moneda:</span>
        <select class="form-control input-sm" data-field="moncodi" data-name="moncodi" name="moneda">
          @foreach( cacheHelper('moneda.all') as $moneda )
          <option value="{{ $moneda->moncodi }}">{{ $moneda->descripcion }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">T.Cambio</span>
        <input class="form-control input-sm" data-field="tipocambio" name="VtaTcam">
      </div>
    </div>
  </div>
  {{-- row --}}

  <!-- data-minimuminputlength="0" -->

  {{-- row --}}
  <div class="row nota">
    <div class="form-group col-md-12" style="overflow: hidden;">
      <div class="input-group">
        <span class="input-group-addon">N° Ant/NC.:</span>
        <select style="display:none" class="form-control input-sm select2" data-settings="{{ json_encode(['minimuminputlength' => 0]) }}" data-url="{{ $urlSearchNC }}" data-id="" data-text="" name="nota_credito_id">
        </select>
        <span class="input-group-addon" style="margin-bottom: 0">
          <a href="#" class="" id="notCredito">
            <span class="fa fa-plus"></span>
          </a>
        </span>
      </div>
    </div>
  </div>
  {{-- row --}}

  <div class="row">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Importe:</span>
        <input class="form-control input-sm" data-pago="campo" data-field="importe" data-db="VtaSald" name="importe">
      </div>
    </div>
  </div>

</div>
<!-- /facto datos -->