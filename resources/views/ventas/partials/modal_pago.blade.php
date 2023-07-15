@php
  $id = $compra->CpaOper ?? 'XXX';
  $type = $type ?? 'venta';
  $isVenta = $type == "venta";
  $urlSearchNC = $isVenta ? route('ventas.nota_credito.topay',1) : route('ventas.nota_credito.topay',0);
  $urlPaymentStatus = $isVenta ? route('ventas.paymentStatus') : route('compras.paymentStatus', $id );
  $urlStore = $isVenta ? route('pagos.venta.store') : route('pagos.compra.store');
  $urlEdit = $isVenta ? route('pagos.venta.update', 'XXX') : route('pagos.compra.update', 'XXX');
  $urlShow = $isVenta ? route('pagos.venta.show', 'XXX') : route('pagos.compra.show', 'XXX');
  $urlDelete = $isVenta ? route('pagos.venta.store') : route('pagos.compra.store');
@endphp


<div
  class="modal modal-seleccion fade" 
  id="modalPago"
  data-urlStatus="{{ $urlPaymentStatus }}"
  data-urlpagos="{{ $urlPaymentStatus }}"
  data-urlsave="{{ $urlStore }}"
  data-urlstore="{{ $urlStore }}"
  data-urledit="{{ $urlEdit }}"
  data-urlshow="{{ $urlShow }}"
  data-urldelete="{{ $urlDelete }}"
>
{{----------------------------------------------------------}}
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header"> 
        <div class="modal-title">
          <h4 class="title-pago">
            <span class="descripcion">Nuevo Pago</span> 
            <span class="pay-correlative data-important"></span> 
          </h4>  
        </div>
        </div>
      <div class="modal-body">
        <input type="hidden" data-field="id" class="vtaoper" data-db="VtaOper">
        @include('ventas.partials.modal_pago.header')
        @include('ventas.partials.modal_pago.importe')
        @include('ventas.partials.modal_pago.calculadora')
        @include('ventas.partials.modal_pago.banco')
      </div>
    </div>
  </div>
{{----------------------------------------------------------}}
</div>