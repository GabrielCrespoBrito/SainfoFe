@php
	$thead = [
    '#',
    'B/S',
    'Base',
    'Código',
    'Unidad',
    'Descripción',
    'Marca',
    [ 'class_name' => 'td_number td-number' , 'text' => 'Cantidad'],
    [ 'class_name' => 'td_number td-number' , 'text' => 'Precio'],
    [ 'class_name' => 'td_number td-number' , 'text' => 'Dcto %'],
    [ 'class_name' => 'td_number td-number' , 'text' => 'Importe']
  ];
	if($create || $modify  ) array_push($thead,'&nbsp;');
	global $productosError, $index;
	$productosError = [];
	$index = 1;
@endphp
{{-- create_tds --}}
@component('components.table' , ['thead' => $thead , 'id' => "table-items" , 'class_name' => 'sainfo' ])
@slot('body')
	@if( $create && $import )
		@include('cotizaciones.partials.factura.table.tbody_create')
	@else
		@include('cotizaciones.partials.factura.table.tbody_edit')
	@endif
@endslot
@endcomponent

@includeWhen(count($productosError), 'cotizaciones.partials.factura.table.table_error',[] )
