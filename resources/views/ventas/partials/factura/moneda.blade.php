<div class="row" style="padding-bottom: 5px">

  <div class="form-group col-md-3">
    <div class="input-group">
      <span class="input-group-addon">Medio Pago</span>
      @if( $create )
      <select name="medio_pago" class="form-control input-sm">
        @foreach( $medios_pagos as $medio_pago )
        <option {{ $medio_pago->isDefault() ? 'selected=selected' : '' }}  value="{{ $medio_pago->tipo_pago_parent->TpgCodi }}">{{ $medio_pago->tipo_pago_parent->descripcion }}</option>
        @endforeach
      </select>
      @else
      <input readonly="readonly" data-id="{{ $venta->TpgCodi }}" name="medio_pago" class="form-control input-sm" value="{{ $venta->getMedioPagoNombre() }}">
      @endif
    </div>
  </div>  

@if($create)
  <div class="col-md-9">
    <a href="#" class="btn btn-flat btn-xs open-data open-venta-aditional-info btn-default btn-block-sm" data-element="general">Información adicional</a>
    <a href="#" class="btn btn-flat pull-right btn-xs btn-primary item-accion crear"> <span class="fa fa-plus"></span> Agregar producto</a>
  </div>
@endif

</div>

<div class="row {{ $create ?  'hide' : '' }} " data-target="general">  

    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">Tipo cambio </span>
        <input name="tipo_cambio" disabled class="form-control input-sm text-center" value="{{ $create ? $tipo_cambio : $venta->VtaTcam }}" type="text">              
      </div>
    </div>


    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">Vendedor </span>
        @if($create)
        <select name="vendedor" data-namedb="VenCodi" class="form-control input-sm">
          @foreach( $vendedores as $vendedor )
          <option {{ $vendedor->isUserLoginVendedor() ? 'selected=selected' : ''   }} value="{{ $vendedor->Vencodi }}">{{ $vendedor->vennomb }}</option>
          @endforeach
        </select>
        @else
        <input name="vendedor" readonly="readonly" class="form-control input-sm" value="{{ $venta->vendedor->vennomb }}">        
        @endif
      </div>
    </div>


{{--  Zona --}}
    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">Zona </span>
        @if($create)
        <select name="zona" data-namedb="zona" class="form-control input-sm">
          @foreach( $zonas as $zona )
          <option value="{{ $zona->ZonCodi }}">{{ $zona->ZonNomb }}</option>
          @endforeach
        </select>
        @else
        <input name="zona" readonly="readonly" class="form-control input-sm" value="{{ $venta->getZona()->ZonNomb }}">        
        @endif
      </div>
    </div>
{{--  Zona --}}

  <div class="form-group col-md-2">  
    <div class="input-group">
      <span class="input-group-addon">Nro Pedido</span>
      <input name="nro_pedido" {{ isset($venta) ? 'readonly=readonly' : '' }} class="form-control input-sm" value="{{ isset($venta) ? $venta->VtaPedi : '' }}" type="text">
    </div>
  </div>

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Doc Relacionado</span>
      @if($create)      
      <input name="" class="form-control input-sm" value="" type="text">
      @else
      <input class="form-control input-sm" readonly="readonly" value="{{ $venta->VtaPedi }}" readonly="readonly">            
      @endif
    </div>
  </div>


</div>


<div class="row {{ $create ?  'hide' : '' }}" data-target="general">  

  <div class="form-group col-md-6">  
    <div class="input-group">
      <span class="input-group-addon">Observación</span>
      <input name="observacion" maxlength="100" class="form-control input-sm" {{ isset($venta) ? 'readonly=readonly' : '' }} value="{{ isset($venta) ? $venta->VtaObse : '' }}" type="text">
    </div>
  </div>


   <div class="form-group col-md-2">
     <div class="input-group">
       <span class="input-group-addon">Placa</span>
       <input name="placa_vehiculo" style="text-transform:uppercase" maxlength="8" class="form-control input-sm" {{ isset($venta) ? 'readonly=readonly' : '' }} value="{{ isset($venta) ? $venta->getPlaca() : '' }}" type="text">
     </div>
   </div>





  <div class="form-group col-md-2">  
    <div class="input-group">      
      <span class="input-group-addon">Factura Guia</span>
      <span class="input-group-addon">
      <input type="checkbox" data-guia="T001-000001" name="guia_remision">
      </span>  
    </div>
  </div>


</div>


<div class="row {{ $create ?  'hide' : '' }}" data-target="general">  

<div class="form-group col-md-1">  
  <div class="input-group">
    <label>
    <span class="input-group-addon">Anticipo <input id="anticipoChecked" {{ $create ? '' : 'disabled=disabled' }} type="checkbox" name="anticipoChecked"  {{ $create ? '' : ($venta->hasAnticipo() ? 'checked=checked' : '') }}  value="1">   </span>
    </label>
  </div>
</div>

<!-- Buscar documento -->
<div class="form-group col-md-7" style="overflow:hidden">  
    <div class="input-group">
      <span class="input-group-addon"> Documento </span>
      @if( $create )
      <input class="form-control input-sm" name="documento_anticipo" placeholder="Numero documento" type="hidden">
      <div class="fixed_position">
        <select id="documento_anticipo" disabled data-settings="{{ json_encode([ 'allowClear' => true, 'placeholder' => 'Buscar Documento de anticipo' , 'theme' => 'default container-cliente-search' ]) }}" data-url="{{ route('ventas.documento_anticipo') }}" name="documento_anticipo" class="form-control input-sm" style="position:absolute;"></select>
      </div>
      @else
      <input class="form-control input-sm" value="{{ $venta->cliente->PCRucc . ' - ' . $venta->cliente->PCNomb  }}" readonly="readonly">
      @endif
    </div>
  </div>

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Total </span>
    <input name="VtaTotalAnticipo" min="0" class="form-control input-sm"  readonly value="{{ $create ? '' : $venta->VtaTotalAnticipo  }}" type="number">
    </div>
  </div>


</div>

{{-- i want to fucking sleep @ medios_pagos --}}