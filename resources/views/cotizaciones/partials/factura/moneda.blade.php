@if($create || $modify)
<div class="row" style="padding-bottom: 5px">
  <div class="col-md-12">
    <a href="#" class="btn btn-xs open-data btn-default" data-target="general">Información adicional</a>
      <a href="#" class="btn pull-right btn-xs btn-primary item-accion crear"> <span class="fa fa-plus"></span> Agregar producto</a>
  </div>
</div>
@endif

<div data-element="general" style="display: none">

  <div class="row">
    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Forma pago </span>
        <select name="forma_pago" class="form-control input-sm">
          @foreach( $forma_pagos as $forma_pago )
          <option 
            data-dias="{{ $forma_pago->condias }}"
            value="{{ $forma_pago->conCodi }}"
            @if($create == false) 
              {{ ($cotizacion->forma_pago->connomb ==  $forma_pago->conCodi) ? 'selected' : '' }} 
            @endif
            >
            {{ $forma_pago->connomb }}
          
          </option>
          @endforeach
        </select> 
      </div>
    </div>
    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Moneda </span>
          <select name="moneda" class="form-control input-sm">            
            @foreach( $monedas as $moneda )

            <option 
            data-esSol="{{ (int) $moneda->esSol() }}" 
            value="{{ $moneda->moncodi }}"
            @if($create == false) 
              {{ ($cotizacion->moncodi ==  $moneda->moncodi ) ? 'selected' : '' }}
            @endif
            > {{ $moneda->monnomb }}
            </option>

            @endforeach  
          </select>               
      </div>
    </div>
    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Tipo de cambio</span>
        <input name="tipo_cambio" required="required" class="form-control input-sm" value="{{ $create ? $tipo_cambio : $cotizacion->CotTCam }}" {{ $create ? '' : 'readonly=readonly' }} type="text">              
      </div>
    </div>


          {{-- <option {{ $vendedor->isUserLoginVendedor() ? 'selected=selected' : '' }} value="{{ $vendedor->Vencodi }}">{{ $vendedor->vennomb }}</option> --}}

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Vendedor </span>
        <select name="vendedor" data-namedb="VenCodi" class="form-control input-sm">        
          @foreach( $vendedores as $vendedor )          
          @php
            $selected = $create ? $vendedor->isUserLoginVendedor() : ($cotizacion->vencodi == $vendedor->Vencodi);
          @endphp
          <option {{ $selected ? 'selected=selected' : '' }} value="{{ $vendedor->Vencodi }}">{{ $vendedor->vennomb }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>

  <div class="row">  
    <div class="form-group col-md-5">  
      <div class="input-group">
        <span class="input-group-addon">Contacto</span>
        <input name="contacto" required="required" class="form-control input-sm" value="{{ $create ? '' : $cotizacion->Cotcont }}" type="text">
      </div>
    </div>

{{--  Zona --}}
    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Zona </span>
        @if( $create || $modify)
        <select name="zona" data-namedb="zona" class="form-control input-sm">
          @foreach( $zonas as $zona )
          <option {{ return_strs_if( $zona->ZonCodi == optional($cotizacion ?? null)->zoncodi , 'selected=selected' )   }}  value="{{ $zona->ZonCodi }}">{{ $zona->ZonNomb }}</option>
          @endforeach
        </select>
        @else
        <input name="zona" readonly="readonly" class="form-control input-sm" value="{{ $cotizacion->getZona()->ZonNomb }}">        
        @endif
      </div>
    </div>
{{--  Zona --}}


    <div class="form-group col-md-4">  
      <div class="input-group">
        <span class="input-group-addon">Doc Referencia</span>
          <input name="doc_ref" class="form-control input-sm"  value="{{ $create ? '' : $cotizacion->Docrefe }}" type="text">
      </div>
    </div>
  </div>

  @php
    // $observacion = $observacion ;
    if( $create && $import ){
        $observacion ='Tienda - #'. $importInfo['id'];
    }
    else {
      $observacion = $create ? '' : $cotizacion->cotobse;
    }
  @endphp

  <div class="row">  
    <div class="form-group col-md-12">  
      <div class="input-group">
        <span class="input-group-addon">Observación </span>
        <input name="observacion" class="form-control input-sm" value="{{ $observacion }}">        
      </div>
    </div>
  </div>

</div>