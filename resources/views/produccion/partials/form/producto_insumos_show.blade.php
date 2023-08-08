{{-- @dd( $produccion->items ) --}}

@foreach( $produccion->items as $item )

@include('produccion.partials.form.producto_plantilla_show', [ 
  'producto' => $item->mandetNomb, 
  'cantidad' => $item->mandetCant,
])

@endforeach