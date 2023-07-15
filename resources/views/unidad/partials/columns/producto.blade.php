@php


// $producto_nombre = $model->producto()->ProNomb;
// $producto_route = route('unidad.mantenimiento' , $model->producto()->ID);

// if( $model->producto_filter  ){
// 	$producto_route = );
// 	$producto_nombre =  $model->producto_filter->ProNomb;
// }

// $producto_nombre = "-";
// $producto_route = "#";

// if( $model->producto_filter  ){
// 	$producto_route = route('unidad.mantenimiento' , $model->producto_filter->ID );
// 	$producto_nombre =  $model->producto_filter->ProNomb;
// }

@endphp

<a target="_blank" href="{{ route('productos.unidad.mantenimiento' , $model->id_producto ) }}">  {{ $model->ProNomb }} </a>


