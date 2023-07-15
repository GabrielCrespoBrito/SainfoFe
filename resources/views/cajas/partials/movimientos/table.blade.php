@component('components.table', ['id' => 'table_movimiento' , 'class_name' => 'datatable' , 'thead' => ["Nro Oper", 'Mov Caj' , "Documento", "Tipo" , "S./" , "USD" , "Acciones"] ])

@slot('body')

@forelse( $movimientos as $movimiento )

@if( $movimiento->isCaja() )
@continue
@endif

@php
# Ingresos
$links = [];

if($movimiento->isIngresoVenta()){
$links[] = [
'src' => route('ventas.show',
[ 'id_factura' => optional($movimiento->venta_pago)->VtaOper]) ,
'texto' => 'Ver',
'target' => '_blank'
];
}
elseif( $movimiento->isOtrosIngresos() ){
$links[] = ['src' => '#' , 'class' => 'show-ingreso' , 'texto' => 'Ver' ];
$links[] = ['src' => '#' , 'class' => 'edit-ingreso' , 'texto' => 'Modificar' ];
$links[] = ['src' => route('cajas.borrar_movimiento') , 'class' => 'delete-ingreso' , 'texto' => 'Eliminar' ];
}
else {

if( $movimiento->isEgresoCompra() ){
if( $movimiento->compra_pago ){
$links[] = [
'src' => route('compras.show', $movimiento->compra_pago->CpaOper),
'class' => 'show-egreso' ,
'texto' => 'Ver',
'target' => '_blank'
];
}
}

else {
$links[] = ['src' => route('cajas.imprimir_egreso', $movimiento->Id ) , 'class' => '' , 'texto' => 'Imprimir', 'target' => '_blank' ];
$links[] = ['src' => '#' , 'class' => 'show-egreso' , 'texto' => 'Modificar' ];
$links[] = ['src' => route('cajas.borrar_movimiento') , 'class' => 'delete-egreso' , 'texto' => 'Eliminar' ];
}

}

@endphp

<tr data-info="{{ $movimiento }}">
  <td>{{ $movimiento->Id }}</td>
  <td>{{ $movimiento->MocNume }}</td>
  <td>{{ $movimiento->documentoRef() ?? "-" }}</td>
  <td>{{ $movimiento->MOTIVO  }} </td>
  <td class="text-right">{{ $is_ingreso ? $movimiento->CANINGS : $movimiento->CANEGRS }}</td>
  <td class="text-right">{{ $is_ingreso ? $movimiento->CANINGD : $movimiento->CANEGRD }}</td>
  {{-- @dd($links) --}}
  <td> @include('partials.column_accion', ['links' => $links ]) </td>
</tr>

@empty

<tr>
  <td> No existen registros </td>
  <td> </td>
  <td> </td>
  <td> </td>
  <td> </td>
  <td> </td>
  <td> </td>
</tr>
@endforelse

@endslot

@endcomponent