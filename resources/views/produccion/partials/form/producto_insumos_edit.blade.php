@foreach( $produccion->items as $item )

@include('produccion.partials.form.producto_plantilla', [ 
  'nameInputProducto' => 'producto_insumo_id[]', 
  'nameInputCantidad' => 'producto_insumo_cantidad[]',
  'id' => $item->mandetCodi,
  'text' => $item->mandetNomb,
  'cantidad' => $item->mandetCant,
  'deleteBtn' => true, 
])

@endforeach
