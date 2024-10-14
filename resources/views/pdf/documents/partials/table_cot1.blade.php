@php


$completar_tds = $completar_tds ?? false;
$class_name = $class_name ?? '';
$borderTbody = $borderTbody ?? true;
$class_name_table = $class_name_table ?? '';
$thead_class = $thead_class ?? '';
$tbody_class = $tbody_class ?? '';

$theads = [
[
'class_name' => 'bg-black c-white text-left pl-x3',
'text' => '#',
],

[
'class_name' => 'bg-black c-white pl-x6 ',
'text' => 'Codigo',
],

[
'class_name' => 'bg-black c-white pl-x6',
'text' => 'Descripción',
],

[
'class_name' => 'bg-black c-white pr-x6 ',
'text' => 'Unid.',
],

[
'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
'text' => 'P/Kgs.',
],

[
'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
'text' => 'Cant.',
],

];


/*
if($orden_campos['valor_unitario'] ){
$theads[] = [
'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
'text' => 'V.Unit'
];
}
*/

//if($orden_campos['precio_unitario'] ){
  
  $theads[] = [
  'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
  'text' =>   'P.Unit',
  ];

//}

$theads[] = [
'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
'text' => 'Importe',
];

$cant_items_add = $cant_items_add ?? 25;
$cant_items_add = $completar_tds ? ($cant_items_add - count($items)) : false;

@endphp

<div class="items {{ $class_name }}">
  <table class="table_items_class {{ $class_name_table }}">
    <thead>
      <tr class="tr_head">
        @foreach( $theads as $td )
        <td class="{{ $td['class_name'] }} {{ $thead_class }}"> {{ $td['text'] }} </td>
        @endforeach
    </thead>
    <tbody class="{{ $tbody_class }}">
      @foreach( $items as $item )
      <tr>
        <td class="{{ $borderTbody ?  'border-left-style-solid' : '' }} pl-x3 vertical-align-top">{{ $item->DetItem }}</td>
        <td class="pl-x6 vertical-align-top">{{ $item->DetCodi }}</td>
        <td class="pl-x6 text-left vertical-align-top">{{ $item->DetNomb }}
          @if( $item->DetDeta ) <br>{!! $item->DetDetaFormat() !!} @endif
        </td>
        <td class="pr-x6 vertical-align-top pl-x3">{{ $item->DetUnid }}</td>
        <td class="text-right pr-x3 vertical-align-top">{{ fixedValue($item->DetPeso) }}</td>
        <td class="text-right {{ $borderTbody ? 'border-right-style-solid border-left-style-solid' : ''  }}  pr-x3 pl-x3 vertical-align-top">{{ $item->DetCant }}</td>

        {{-- @if($orden_campos['valor_unitario'] )
        <td class="text-right border-right-style-solid pr-x3 pl-x3 vertical-align-top">
          {{ decimal( optional($item)->valorUnitario(), $decimals ) }}
        </td>
        @endif --}}

        <td class="text-right {{ $borderTbody ? 'border-right-style-solid' : ''  }}  pr-x3 pl-x3 vertical-align-top"> {{ $item->getPrecio($orden_campos['precio_unitario']) }}</td>

        {{-- @if($orden_campos['precio_unitario'] ) --}}
        {{-- <td class="text-right border-right-style-solid pr-x3 pl-x3 vertical-align-top"> {{ decimal($item->precioUnitario(), $decimals ) }}</td> --}}
        {{-- @endif --}}

        <td class="text-right {{ $borderTbody ? 'border-right-style-solid' : ''  }} pr-x3 pl-x3 vertical-align-top"> {{ decimal( $item->DetImpo ) }}</td>

      </tr>
      @endforeach
      
      @if( $completar_tds && $cant_items_add)
        @for ($i = 0; $i < $cant_items_add ; $i++)
        <tr>
        <td class="border-left-style-solid pl-x3"></td>
        <td class="pl-x6">&nbsp;</td>
        <td class="pl-x6 text-left"></td>
        <td class="pr-x6 pl-x3"></td>
        <td class="pr-x3"></td>
        <td class="border-right-style-solid border-left-style-solid pr-x3 pl-x3"></td>
        @if($orden_campos['valor_unitario'] )
        <td class="border-right-style-solid pr-x3 pl-x3"></td>
        @endif
        @if($orden_campos['precio_unitario'] )
        <td class="border-right-style-solid pr-x3 pl-x3"></td>
        @endif
        <td class="border-right-style-solid pr-x3 pl-x3"></td>
      </tr>
        @endfor
      @endif

    </tbody>
  </table>
</div>