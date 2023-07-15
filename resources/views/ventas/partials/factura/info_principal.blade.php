<div class="row">

  <div class="form-group col-md-3">
    <div class="input-group">
      <span class="input-group-addon">Forma pago </span>
      @if($create)
      <select name="forma_pago" class="form-control input-sm">
        @foreach( $forma_pagos as $forma_pago )
        <option data-info="{{ $forma_pago->dias }}" data-credit="{{ (int) ($forma_pago->dias->count() > 1) }}" data-dias="{{ $forma_pago->condias }}" value="{{ $forma_pago->conCodi }}"> {{ $forma_pago->connomb }} </option>
        @endforeach
      </select>

      <span class="input-group-addon addon-btn-modal-pago" style="display:none">
        <a href="#" class="btn btn-xs btn-default btn-forma-pago"> <span class="fa fa-money"></span> </a>
      </span>

      @else
      <input name="forma_pago" readonly="readonly" class="form-control input-sm" value="{{ $venta->forma_pago->connomb }}">

      @if($venta->forma_pago->isCredito())
      <span class="input-group-addon"> <a href="#" class="btn btn-xs btn-default modal-guia-viewer"> <span class="fa fa-eye"></span> </a></span>
      @endif

      @endif
    </div>
  </div>

  <div class="form-group col-md-2">
    <div class="input-group">
      <span class="input-group-addon">Moneda </span>
      @if( $create )
      <select name="moneda" class="form-control input-sm">
        @foreach( $monedas as $moneda )
        <option data-esSol="{{ (int) $moneda->esSol() }}" value="{{ $moneda->moncodi }}">{{ $moneda->monnomb }}</option>
        @endforeach
      </select>
      @else
      <input name="moneda" readonly="readonly" class="form-control input-sm" value="{{ $venta->getNombreMoneda() }}">
      @endif
    </div>
  </div>


  <div class="form-group col-md-2 col-sm-6 col-xs-6">
    <div class="input-group">
      <span class="input-group-addon">F.Emis</span>
      @if($create)
      <input name="fecha_emision" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ datePeru('Y-m-d') }}" required="required" class="form-control input-sm datepicker" value="{{ datePeru('Y-m-d') }}" type="text">
      @else
      <input class="form-control input-sm " value="{{ $venta->VtaFvta }}" readonly="readonly">
      @endif
    </div>
  </div>

  <div class="form-group col-md-2 col-sm-6 col-xs-6">
    <div class="input-group">
      <span class="input-group-addon">F.Venc</span>
      @if($create)
      <input name="fecha_referencia" required="required" data-date-format="yyyy-mm-dd" class="form-control input-sm datepicker" value="{{ datePeru('Y-m-d') }}" type="text">
      @else
      <input class="form-control input-sm " value="{{ $venta->VtaFVen }}" readonly="readonly">
      @endif
    </div>
  </div>


  {{-- Guia Nueva --}}
  <div class="form-group col-md-3">
    <div class="input-group">
    
      <span class="input-group-addon">Guia  
        @if($create)
        <a href="#" class="btn-guia-tipo-action btn btn-xs btn-flat btn-default modal-guia-viewer" style="{{ $verificar_almacen == '0' ? 'display:none' : '' }}"> <span class="fa fa-eye"></span> </a> 

        <a href="#" data-toggle="modal" data-target="#modalAsocGuia" class="btn-guia-tipo-action btn btn-xs btn-flat btn-default btn_guia_asoc" style="display:none"> <span class="fa fa-pencil"></span> </a> 
        @endif
      </span>

      @if($create)
      <span class="input-group-addon input-group-adddon-hide">   
      <select  name="guia_tipo_asoc" class="form-control input-sm guia_action">
        <option {{ $verificar_almacen == 0 ? 'selected=selected' : '' }} value="sin_guia"> SIN GUIA </option>
        <optgroup label="Guia Remisión">
          <option {{ $verificar_almacen == 1 ? 'selected=selected' : '' }} value="nueva_interna"> NUEVA INTERNA </option>
          <option {{ $verificar_almacen == 2 ? 'selected=selected' : '' }} value="nueva_electronica"> NUEVA ELECTRONICA </option>
          <option value="asociar"> ASOCIAR </option>
        </optgroup>
        <optgroup label="Guia Transportista">
          <option {{ $verificar_almacen == 3 ? 'selected=selected' : '' }} value="transportista"> TRANSPORTISTA </option>
        <optgroup>
      </select>
      </span>

      @else

      <!--  -->
      <span class="input-group-addon">
        @if( $has_guia_referencia )
        <a target="_blank" href="{{ $guiaRoute }}" class="btn btn-block btn-default btn-xs"> {{ $guiaNombre }} </a>
        @else

        @if( $has_guias_asoc )
        <a href="#modalAsocGuia" data-toggle="modal" class="btn btn-xs btn-block btn-default"> Guias Remisión ({{ $guias_count }}) </a>
        @else
        <a href="#" class="btn btn-xs btn-block btn-default disabled"> Sin Guia </a>
        @endif
        @endif
      </span>
      <!--  -->

      @endif
    </div>
  </div>
  {{-- /Guia Nueva --}}

</div>