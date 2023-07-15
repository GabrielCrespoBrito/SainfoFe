@if($create)
<div class="row" style="padding-bottom: 5px">
  <div class="col-md-12">
    <a href="#" class="btn btn-xs open-data btn-default" data-element="general">Información adicional</a>
      @if($guia->canModify())
        <a href="#" class="btn pull-right btn-xs btn-primary item-accion crear"> <span class="fa fa-plus"></span> Agregar producto</a>
      @endif
  </div>
</div>
@endif

<div class="row {{ $create ?  'hide' : '' }} " data-target="general">  

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Moneda </span>
          @if( ! $show )
          <select name="moneda" class="form-control input-sm">            
            @foreach( $monedas as $moneda )
            <option {{ $moneda->moncodi === $guia->moncodi }} data-esSol="{{ (int) $moneda->esSol() }}" value="{{ $moneda->moncodi }}">{{ $moneda->monnomb }}</option>
            @endforeach  
          </select>   

          @else 
          <input name="moneda" readonly="readonly" class="form-control input-sm" value="{{ $guia->moneda->monnomb }}">    
          @endif             
      </div>
    </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Tipo cambio </span>
        <input name="tipo_cambio" required="required" class="form-control input-sm" value="{{ !$info ? $tipo_cambio : $guia->guiTcam }}" {{ $create ? '' : 'readonly=readonly' }} type="text">              
      </div>
    </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Forma pago </span>
        @if($create)
        <select name="forma_pago" class="form-control input-sm">
          @foreach( $forma_pagos as $forma_pago )
          <option {{ $guia->concodi  === $forma_pago->conCodi  }} data-dias={{ $forma_pago->condias }} value="{{ $forma_pago->conCodi }}"> {{ $forma_pago->connomb }} </option>
          @endforeach
        </select>
        @else
          <input name="forma_pago" readonly="readonly" class="form-control input-sm" value="{{ $guia->forma_pago->connomb }}">
        @endif
      </div>
    </div>

    <div class="form-group col-md-3">  
      <div class="input-group">
        <span class="input-group-addon">Vendedor </span>
        @if($create)
          <select name="vendedor" data-namedb="VenCodi" class="form-control input-sm">
            @foreach( $vendedores as $vendedor )
            <option {{ $guia->vencodi  === $vendedor->Vencodi  }}  value="{{ $vendedor->Vencodi }}">{{ $vendedor->vennomb }}</option>
            @endforeach
          </select>
        @else
          <input name="vendedor" readonly="readonly" class="form-control input-sm" value="{{ $venta->vendedor->vennomb }}">
        @endif
      </div>
    </div>



</div>


<div class="row {{ $create ?  'hide' : '' }}" data-target="general">  

  <div class="form-group col-md-2 no_pr">  
    <div class="input-group">
      <span class="input-group-addon">Nro Pedido</span>
      <input name="nro_pedido" value="{{ $guia->guipedi }}"" {{ $show ? 'readonly=readonly' : '' }} required="required" class="form-control input-sm" type="text">
    </div>
  </div>

  <div class="form-group col-md-2">  
    <div class="input-group">

      <span class="input-group-addon">Doc ref</span>

        <input name="doc_ref" {{ $accion == "edit" || $importar ? 'readonly=readonly' : '' }} value="{{ $importar ? $importar->VtaNume : $guia->docrefe }}" class="form-control input-sm" value="" type="text">

    </div>
  </div>

 <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Fecha emisión</span>
      <input name="fecha_emision" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ datePeru('Y-m-d') }}" required="required"  class="form-control input-sm datepicker" value="{{ $info ? $guia->GuiFemi : datePeru('Y-m-d') }}" type="text">
    </div>
  </div>

 <div class="form-group col-md-4">  
    <div class="input-group">
      <span class="input-group-addon">Fecha Despacho</span>    
      <input name="fecha_despacho" class="form-control input-sm " readonly value="{{ $info ? $guia->GuiFDes : '' }}" type="text">

    </div>
  </div>

</div>

<div class="row {{ $create ?  'hide' : '' }}" data-target="general">  
  <div class="form-group col-md-12">  
    <div class="input-group">
      <span class="input-group-addon">Observación</span>
      <input name="observacion" required="required" class="form-control input-sm" value="{{ $info ? $guia->observacion : '' }}" {{ $show ? 'readonly=readonly' : '' }} type="text">
    </div>
  </div>
</div>








