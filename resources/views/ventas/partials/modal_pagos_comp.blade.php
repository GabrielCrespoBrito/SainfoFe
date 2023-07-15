@php

$type = $type ?? "venta";

if( $type == "venta" ){  
  $title = "Pagos de venta";
  $urlIndex = route('ventas.pagos');
  $urlDelete = route('ventas.quitar_pago');
}

else {
  $id = $compra->CpaOper ?? 'XXX';
  $title = "Pagos de compra";
  $urlIndex = route('compras.pagos', $id);
  $urlDelete = route( "pagos.compra.delete");  
}

$attrs =sprintf("data-urlpagos='%s' data-urlremove='%s' data-urlCopypagos='%s'" , $urlIndex, $urlDelete, $urlIndex )

@endphp

@component('components.modal', ['id' => 'modalPagos' , 'size' => 'modal-lg' ])

  {{-- 
    modalPagos
    modalPagos
    modalPagos
    modalPagos
    modalPagos
    modalPagos
    modalPagos  
    
  --}}

  @slot('title')      
    <div class="title-div">   

      <span class="title-document">
        <span class="title-documento-nombre"> {{ $title }} </span> 
        <span class="title-documento-correlative data-important" data-field="correlative"></span> 
      </span>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

{{-- 


        <a class="btn btn-primary pull-right btn-flat nuevo_pago"> <span class="fa fa-plus"> </span> </a>
        <span class="title-monto data-important" data-type="monto"> Por pagar
          <span class="moneda" data-field="moneda"></span>
          <span class="cantidad_pagar" data-field="saldo"></span>
        </span> --}}



    </div>

    <div class="title-div">
      <a class="btn btn-primary pull-right btn-flat nuevo_pago"> <span class="fa fa-plus"> </span> </a>
      <span class="title-monto data-important" data-type="monto"> Por pagar
        <span class="moneda" data-field="moneda"></span>
        <span class="cantidad_pagar" data-field="saldo"></span>
      </span>
    </div>


  @endslot

  @slot('body')

    <div class="botones_div" {!! $attrs !!}>
    </div>

    @component('components.table', ['id' => 'table_pagos' , 'thead' => [ 'Nro Oper' , 'Fecha' , 'T. Pago' , 'Moneda','T. Cambio ','Importe' , 'Acciones' ]])
    @endcomponent

  @endslot
@endcomponent