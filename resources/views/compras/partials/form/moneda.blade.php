<div class="row">  
    
    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">Moneda </span>       
          <select name="moncodi_" {{ setInputState($show) }} class="form-control input-sm">            
            @foreach( $monedas as $moneda )
            <option {{  setSelectedOption( $moneda->moncodi , $compra->moncodi  ) }} data-esSol="{{ (int) $moneda->esSol() }}" value="{{ $moneda->moncodi }}">{{ $moneda->monnomb }}</option>
            @endforeach  
          </select>  
          <input type="hidden" name="moncodi">
      </div>
    </div>

    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon">Tipo cambio </span>
        <input name="CpaTCam" {{ setInputState($show) }} required="required" class="form-control input-sm" value="{{  $compra->tipo_cambio ?? $tipo_cambio }}" type="text">
      </div>
    </div>

    <div class="form-group col-md-2">  
      <div class="input-group">
        <span class="input-group-addon"> Comprador </span>
        <select name="VenCodi" {{ setInputState($show) }} data-namedb="VenCodi" class="form-control input-sm">
          @foreach( $vendedores as $vendedor )
          <option {{ setSelectedOption( $compra->VenCodi , $vendedor->Vencodi ) }} value="{{ $vendedor->Vencodi }}">{{ $vendedor->vennomb }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Forma pago </span>
        <select name="concodi" {{ setInputState($show) }} class="form-control input-sm">
          @foreach( $forma_pagos as $forma_pago )
          <option {{ setSelectedOption( $forma_pago->conCodi , $compra->concodi) }} data-dias="{{ $forma_pago->condias }}"" value="{{ $forma_pago->conCodi }}"> {{ $forma_pago->connomb }} </option>
          @endforeach
        </select>
      </div>
    </div>

  {{-- active_form --}}

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Medio Pago </span>
        @if($active_form)
        <select name="TpgCodi" {{ setInputState($show) }} class="form-control input-sm">
          @foreach( $medios_pagos as $medio_pago )
            @php
              $selected = false;

              if( $create && $selected == false ){
                $selected = $medio_pago->isDefault();
              }
              else {
                $selected = $medio_pago->tipo_pago == $compra->TpgCodi;
              }
            @endphp
            <option {{ $selected ? 'selected=selected' : '' }}  value="{{ $medio_pago->tipo_pago_parent->TpgCodi }}">{{ $medio_pago->tipo_pago_parent->descripcion }}</option>                   
          @endforeach
        </select>
        @else
          <input readonly="readonly" data-id="{{ $compra->TpgCodi }}" name="medio_pago" class="form-control input-sm" value="{{ $compra->getMedioPagoNombre() }}">
        @endif
      </div>
    </div>
</div>

<div class="row">  
  <div class="form-group col-md-3">  
    <div class="input-group">
      <span class="input-group-addon">Doc Ref</span>
    <input name="Docrefe" class="form-control input-sm" {{ setInputState($show) }} value="{{ $compra->Docrefe }}" type="text">
    </div>
  </div>

  <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Observacion</span>
    <input 
      name="Cpaobse"
      class="form-control input-sm" {{ setInputState($show) }}
      value="{{ $compra->Cpaobse }}"
      type="text">   
    </div>
  </div>  
  
{{--  Zona --}}
    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Zona </span>
        @if( $active_form)
        <select name="zona" data-namedb="zona" class="form-control input-sm">
          @foreach( $zonas as $zona )
          <option {{ return_strs_if( $zona->ZonCodi == $compra->zoncodi , 'selected=selected' )   }}  value="{{ $zona->ZonCodi }}">{{ $zona->ZonNomb }}</option>

          @endforeach
        </select>
        @else
        <input name="zona" readonly="readonly" class="form-control input-sm" value="{{ $compra->getZona()->ZonNomb }}">        
        @endif
      </div>
    </div>
{{--  Zona --}}
  
  <div class="form-group col-md-2">  
    <div class="input-group">
      <span class="input-group-addon">IGV</span>
        <select {{ $accion == 'create' ? '' : 'disabled=disabled' }}  name="igv_porcentaje" {{ setInputState($show) }} class="form-control input-sm">
          @foreach( $igvOptions as $igv )
          <option 
            {{ setSelectedOption( $igv_porc , $igv->porc ) }}
            data-porc="{{ $igv->porc }}"
            value="{{ $igv->codigo }}"> {{ $igv->descripcion }} % 
          </option>
          @endforeach
        </select>
    </div>
  </div>
</div>