<?php
$cant_items = $cant_items ?? 0;
$complete_tds_spaces = $complete_tds_spaces ?? false;
$cant_tds_to_add = 0;

if ($complete_tds_spaces) {
  if ($cant_items) {
    $cant_items += (int) $venta2->hasAnticipo();
    ;
    if ($cant_items < 24) {
      $complete_tds_spaces = true;
      $cant_tds_to_add = ($venta2->isNotaVenta() ? 43 : 35) - $cant_items;
    }
  }
}

$class_name = $class_name ?? '';
$class_name_table = $class_name_table ?? '';
$class_tds_body = $class_tds_body ?? '';
$thead_class = $thead_class ?? '';
$thead_importe_class = $thead_importe_class ?? '';
$tbody_class = $tbody_class ?? '';
$class_orden = $class_orden ?? '';
$class_codigo = $class_codigo ?? '';
$class_unidad = $class_unidad ?? '';
$class_descripcion = $class_descripcion ?? '';
$class_cant = $class_cant ?? '';
$class_precio_unit = $class_precio_unit ?? '';
$class_importe = $class_importe ?? '';

$theads = [
  [
    'class_name' => 'bg-black c-white pr-x9 text-center',
    'width' => "5%" ,
    'text' => 'Item',
  ],
  [
    'class_name' => 'bg-black c-white pr-x3 text-center',
    'width' => "10%" ,
    'text' => 'Codigo',
  ],
  [
    'class_name' => 'bg-black c-white pr-x3 text-center',
    'width' => "5%" ,
    'text' => 'Unidad',
  ],
  [
    'class_name' => 'bg-black c-white pr-x3',
    'width' => "52%" ,
    'text' => 'Descripción',
  ],
  [
    'class_name' => 'bg-black c-white pr-x3 text-right',
    'width' => "8%" ,
    'text' => 'Cant.',
  ],
  [
    'class_name' => 'bg-black c-white pr-x3 text-right',
    'width' => "10%" ,
    'text' => 'P.Unit',
  ],
  [
    'class_name' => 'bg-black c-white pr-x3 text-right ' . $thead_importe_class,
    'width' => "10%" ,
    'text' => 'Importe',
  ],
];

?>
<!--  -->
<div style="" class="items {{ $class_name }}">
  <table class="table_items_class {{ $class_name_table }}">
    <thead>
      <tr class="tr_head">
        @foreach( $theads as $td )
        <td class="{{ $td['class_name'] }} {{ $thead_class }}" width="{{ $td['width'] }}"> {{ $td['text'] }} </td>
        @endforeach
    </thead>

    <tbody>
      @php
      $item_add = 0;
      @endphp

      @if( $venta2->hasAnticipo() )
      @php
      $item_add = 1;
      $item = $venta2->getAnticipoItemData();
      @endphp

      <tr>
        <td class="{{ $class_orden }} {{ $tbody_class }}"> 01 </td>
        <td class="{{ $class_codigo }} {{ $tbody_class }} ">{{ $item->procodi }}</td>
        <td class="{{ $class_unidad }} {{ $tbody_class }} ">{{ $item->unidad_abreviatura }}</td>
        <td class="{{ $class_descripcion }} {{ $tbody_class }} ">{{ $item->nombre_producto }}</td>
        <td class="{{ $class_cant }} {{ $tbody_class }} ">{{ $item->cantidad }}</td>
        <td class="{{ $class_precio_unit }} {{ $tbody_class }} ">{{ convertNegativeStr(decimal( $item->totales['precio_unitario'])) }} </td>
        <td class="{{ $class_importe }} {{ $tbody_class }} ">{{ convertNegativeStr(decimal($item->importe)) }}</td>
      </tr>

      @endif

      @foreach( $items as $item )
      <tr>
        <td class="{{ $class_orden }} {{ $tbody_class }} ">{{ addCero( $item->DetItem + $item_add ) }}</td>
        <td class="{{ $class_codigo }} {{ $tbody_class }} ">{{ $item->DetCodi }}</td>
        <td class="{{ $class_unidad }} {{ $tbody_class }} ">{{ $item->DetUnid }}</td>
        <td class="{{ $class_descripcion }} {{ $tbody_class }} "> {{ $item->DetNomb }} 
        @if( $item->DetDeta ) <br>{!! $item->DetDetaFormat() !!} @endif
        </td>
        <td class="{{ $class_cant }} {{ $tbody_class }} ">{{ $item->DetCant }}</td>
        <td class="{{ $class_precio_unit }} {{ $tbody_class }} ">{{ decimal($item->getTotal('precio_unitario'), $decimals) }} </td>
        <td class="{{ $class_importe }} {{ $tbody_class }} ">{{ decimal($item->DetImpo) }}</td>
      </tr>
      @endforeach

      @if( $complete_tds_spaces )
      @for( $i = 0; $i < $cant_tds_to_add; $i++ )
      <td class="{{ $class_orden }}">&nbsp;</td>
        <td class="{{ $class_codigo }}">&nbsp;</td>
        <td class="{{ $class_unidad }}">&nbsp;</td>
        <td class="{{ $class_descripcion }}">&nbsp;</td>
        <td class="{{ $class_cant }}">&nbsp;</td>
        <td class="{{ $class_precio_unit }}">&nbsp;</td>
        <td class="{{ $class_importe }}">&nbsp;</td>
        </tr>
      @endfor
      @endif
    </tbody>
  </table>
</div>


