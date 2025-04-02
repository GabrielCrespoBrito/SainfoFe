@php
	$create = $accion == "create";
	$edit 	= $accion == "edit";
	$show 	= $accion == "show";
	$active_form = $create || $edit;
	$url = "";
	$url = $create ? route('compras.store') : route('compras.update', $compra->CpaOper );
@endphp

@if($active_form)
	@include('clientes.partials.modal_clientes_proveedores', ['title' => 'Crear Proveedor', 'defaultEntity' => 'P' ])
  @include('components.specific.modal_productos', ['nuevo_producto' => false])
  
@endif

@include('components.block_elemento')

<form class="form_principal factura_div focus-green" id="form_principal" data-url="{{ $url }}">		
	
  {{-- Aqui esta el boton para mostrar la guia de ingreso --}}
	@include('compras.partials.form.botones' , compact('create','edit','show','active_form') )

	@include('compras.partials.form.nroventa', compact('create','edit','show','active_form') ) 
	@include('compras.partials.form.cliente' , compact('create','edit','show','active_form') )

	@include('compras.partials.form.toggle_info')
	<div id="info_adicional" class="collapse">
		@include('compras.partials.form.fechas'  , compact('create','edit','show','active_form') )
		@include('compras.partials.form.moneda'  , compact('create','edit','show','active_form') )
	</div>
</form>

@include('compras.partials.form.producto', compact('create','edit','show','active_form')) 
@include('compras.partials.form.table', compact('create','edit','show','active_form')) 
@include('compras.partials.form.totales', compact('create','edit','show','active_form')) 

@if( $show )
	@include('ventas.partials.modal_pagos_comp', ['type' => 'compra'])
	@include('ventas.partials.modal_pago' , ['type' => 'compra'] )
@endif


@if( $create )
	@include('compras.partials.modal_importacion', ['type' => 'compra'])
@endif