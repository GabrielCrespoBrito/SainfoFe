<div class="seccion seccion-acciones">

  <div class="title-section"> Acciones </div>


      <div class="col-md-12">
        <a href="#" class="btn btn-xs btn-default btn-block accion_email"> <span class="fa fa-envelope"></span> Enviar Email </a>  
      </div>

      <div class="col-md-12">
        <a href="#" target="_blank" data-text="{{ $venta->getMessageLink() }}" class="btn btn-xs btn-default btn-block send-whatapp"> <span style="color: #45c554;" class="fa fa-whatsapp"></span> Enviar WhatApp </a>  
      </div>
    
      <div class="col-md-12 pb-x10 div-number-whatapp" style="display:none">
        <input placeholder="Nùmero de Telefono" type="number" name="numberWhatApp" class="input-sm input-sm form-control text-center" value="{{ $venta->cliente->PCTel1 }}">
      </div>

    <!-- {{-- Guia --}}
    <div class="col-md-12">
      @if( !is_null( $venta->guia ) )
        @if( $venta->guia->isSendSunat() )
          <a href="{{ route('guia.show', $venta->guia->GuiOper) }}" class="btn btn-xs btn-success btn-block"> <span class="fa fa-bus"></span> Guia remisión (Enviada sunat) </a>
        @else
          <a href="{{ route('guia.show', $venta->guia->GuiOper ) }}" class="btn btn-xs btn-default btn-block "> <span class="fa fa-bus "></span> Guia remisión (Por enviar a la sunat) </a>
        @endif
      @else
        <a href="#" class="btn btn-xs btn-default btn-block asignarguia"> <span class="fa fa-bus "></span> Guia remisión (sin guia) </a>  
      @endif
    </div> -->

    @if( $venta->isNotaVenta() )

      <div class="col-md-12">
        <a href="#" data-url="{{ route('ventas.anular_nv', [ 'id' => $venta->VtaOper ] ) }}" class="btn btn-xs btn-danger btn-block accion_anular">
          <span class="fa fa-ban"></span> Anular
        </a>
      </div>
  
    @endif

    {{-- Consultar estado en la sunat --}}
    <div class="col-md-12">
      <a 
        href="#" 
        data-url="{{ route('ventas.consult_sunat', [ 'id' => $venta->VtaOper ] ) }}" 
        class="btn btn-xs btn-default btn-block accion_consult"> 
        <span class="fa fa-envelope"></span> Consultar estado sunat 
      </a>
    </div>

    {{-- Consultar estado cdr en la sunat --}}
    <div class="col-md-12">
      <a href="{{ route('ventas.consult_sunat_cdr', ['id' => $venta->VtaOper]) }}" 
        class="btn btn-xs btn-default btn-block"> 
        <span class="fa fa-envelope"></span> Consultar CDR 
      </a>
    </div>

    @php
      $isProduccion = get_empresa()->produccion();
      $canCreateNC = $isProduccion ? $venta->isAvailabledForNotaCredito() : $venta->isAvailabledForNotaDebitoCreditoDemo();
      $canCreateND = $isProduccion ? $venta->isAvailabledForNotaDebito()  : $venta->isAvailabledForNotaDebitoCreditoDemo();
    @endphp

    @if( $canCreateNC )
      <div class="col-md-12">
        <a data-url="{{ route('ventas.showApi', [ 'id' => $venta->VtaOper, 'nc' => '1']  ) }}" data-url_store="{{ route('ventas.createNC',  $venta->VtaOper ) }}" href="#" class="btn btn-default btn-xs btn-block" data-nc="1">
          <span class="fa fa-external-link"></span> Crear Nota de Credito
        </a>
      </div>
    @endif

    @if( $canCreateND )
      <div class="col-md-12">
        <a data-url="{{ route('ventas.showApi', [ 'id' => $venta->VtaOper, 'nc' => '0'] ) }}" data-url_store="{{ route('ventas.createND',  $venta->VtaOper ) }}" href="#" class="btn btn-default btn-xs btn-block" data-nd="1">
          <span class="fa fa-external-link"></span> Crear Nota de Debito
        </a>
      </div>  
    @endif

</div>