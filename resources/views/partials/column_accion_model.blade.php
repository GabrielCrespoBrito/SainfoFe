@php

$table = '';

if( get_class($model) == 'stdClass'){
$table = 'compras_cab';
}
else {
$table = $model->getTable();
}

switch ( $table ) {

case 'compras_cab':
$links = [
['src' => route('compras.show', $model->CpaOper) , 'texto' => 'Ver'],
['src' => route('compras.edit', $model->CpaOper) , 'texto' => 'Edit'],
['src' => route('compras.pdf', $model->CpaOper)  , 'target' => '_blank', 'texto' => 'Pdf'],
['src' => '#' , 'texto' => 'Borrar' , 'class' => 'eliminate-element' , 'id' => $model->CpaOper ]
];
break;

case 'productos_mat_inventario':

$links = [
  ['src' => route('toma_inventario.pdf', $model->id ) , 'texto' => 'PDF'],
  ['src' => route('toma_inventario.show', $model->id ) , 'texto' => 'Ver'],
  ['src' => route('toma_inventario.edit', $model->id ) , 'texto' => 'Modificar'],
  ['src' => "#" , 'texto' => 'Borrar' , 'class' => 'eliminate-element' , 'id' => $model->InvCodi ],
];
break;

// Caja Detalle
case 'caja_detalle':

$links = [
  ['src' => route('toma_inventario.pdf', $model->id ) , 'texto' => 'PDF'],
  ['src' => route('toma_inventario.show', $model->id ) , 'texto' => 'Ver'],
  ['src' => route('toma_inventario.edit', $model->id ) , 'texto' => 'Modificar'],
  ['src' => "#" , 'texto' => 'Borrar' , 'class' => 'eliminate-element' , 'id' => $model->InvCodi ],
];

// ---------------


if( $model->isCaja() ){
  $links = [];
}

else {
  # Ingresos
  $links = [];

  if($model->isIngresoVenta()){
    $links[] = [
    'src' => route('ventas.show',
    [ 'id_factura' => optional($model->venta_pago)->VtaOper]) ,
    'texto' => 'Ver',
    'target' => '_blank'
    ];
  }

  elseif( $model->isOtrosIngresos() ){
    $links[] = ['src' => '#' , 'class' => 'show-ingreso' , 'texto' => 'Ver' ];
    $links[] = ['src' => '#' , 'class' => 'edit-ingreso' , 'texto' => 'Modificar' ];
    $links[] = ['src' => route('cajas.borrar_movimiento') , 'class' => 'delete-ingreso' , 'texto' => 'Eliminar' ];
  }

  else {

    if( $model->isEgresoCompra() ){
      if( $model->compra_pago ){
        $links[] = [
        'src' => route('compras.show', $model->compra_pago->CpaOper),
        'class' => 'show-egreso' ,
        'texto' => 'Ver',
        'target' => '_blank'
        ];
      }
    }

    else {
    $links[] = ['src' => route('cajas.imprimir_egreso', $model->Id ) , 'class' => '' , 'texto' => 'Imprimir', 'target' => '_blank' ];
    $links[] = ['src' => '#' , 'class' => 'show-egreso' , 'texto' => 'Modificar' ];
    $links[] = ['src' => route('cajas.borrar_movimiento') , 'class' => 'delete-egreso' , 'texto' => 'Eliminar' ];
    }

  }
}
// ---------------


break;
// Caja Detalle



case 'wp_posts':
$links = [
['src' => route('tienda.orden.show', $model->ID), 'target' => '_blank', 'class' => 'show-ele' , 'texto' => 'Ver'],
];

// dd( $model->ID, $model->getCotizacionId() );
if($model->isCompleteStatus()){
$text = 'Ver Cotizacion';
$src = route('coti.edit', ['id_cotizacion' => $model->getCotizacionId() ]);
}
else {
$text = 'Generar Cotizacion';
$src = route('tienda.orden.generar-cotizacion', $model->ID);
}
// $src = route('tienda.orden.generar-cotizacion', $model->ID);

$links[] = ['src' => $src , 'target' => '_blank', 'class' => 'show-ele' , 'texto' => $text];
break;

default:
# code...
break;
}

@endphp
@include('partials.column_accion', [ 'links' => $links ])