@php
global $index;  
@endphp




@foreach( $importInfo['items'] as $item )

{{-- @dd($item) --}}

@php
  $productoSainfo = $item['producto'];
    $info = [];
    $unidad  = $productoSainfo->getUnidadPrincipal();
    $unidades  = $productoSainfo->unidades;
    $precio = $unidad->UNIPUVS;
    $total = $precio * $item['cantidad'];
    $info  = json_encode($productoSainfo->getFormatEdicion($precio, $item['cantidad']));
@endphp

  @include('cotizaciones.partials.factura.table.tr',[
  'item' => agregar_ceros($index,2,0),
  'tiecodi' => $productoSainfo->tiecodi,
  'base' => $productoSainfo->BaseIGV,
  'unidad_code' => $productoSainfo->ProCodi,
  'unidad_nombre' => $unidad->withListaName(),
  'nombre' => $productoSainfo->ProNomb,
  'marca' => $productoSainfo->marca->MarNomb,
  'cantidad' => $item['cantidad'],
  'detcodi' => $productoSainfo->ProCodi,
  'precio' => $precio,
  'descuento' => '0',
  'importe' => $total,
  'accion' => true,
  'info' => $info,
  'unidades' => $unidades,
  ])
@php
  $index++;
@endphp

@endforeach