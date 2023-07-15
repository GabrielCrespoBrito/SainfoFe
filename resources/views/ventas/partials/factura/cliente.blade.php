<div class="row">
  <div class="form-group col-md-12" style="overflow:hidden">
    <div class="input-group">
      <span class="input-group-addon open-data" data-element="cliente">Cliente</span>
      @if( $create )
      <input data-namedb="tipo_documento_c" class="form-control input-sm" name="tipo_documento_c" placeholder="Numero documento" type="hidden">
      <div class="fixed_position">
        <select id="cliente_documento" data-cliente_default="{{ $cliente_default }}" data-settings="{{ json_encode([ 'allowClear' => true, 'placeholder' => 'Buscar Cliente' , 'theme' => 'default container-cliente-search' ]) }}" data-url="{{ route('clientes.ventas.search') }}" name="cliente_documento" class="form-control input-sm" style="position:absolute;"></select>
      </div>
      <span class="input-group-addon">
        <a href="#" id="newCliente" class="btn btn-xs btn-default"><span class="fa fa-plus"></span> </a>
      </span>
      @else
      <input class="form-control input-sm" value="{{ $venta->cliente->PCRucc . ' - ' . $venta->cliente->PCNomb  }}" readonly="readonly">
      @endif
    </div>
  </div>
</div>

<div class="row {{ $create ?  'hide' : '' }}  row-cliente-adicional" data-target="cliente">
  <div class="form-group col-md-8">
    <div class="input-group">
      <span class="input-group-addon">Dirección</span>
      <input data-namedb="PCDire" class="form-control input-sm inputs_cliente" name="direccion" readonly="readonly" value="{{ $create ? '' : $venta->cliente->PCDire }}" placeholder="Dirección">
    </div>
  </div>

  <div class="form-group col-md-4">
    <div class="input-group">
      <span class="input-group-addon"> Email</span>
      <input data-namedb="PCMail" class="form-control input-sm inputs_cliente" name="email" readonly="readonly" value="{{ $create ? '' : $venta->cliente->PCMail }}" placeholder="Email">
    </div>
  </div>

</div>