@php

$class_name = $class_name ?? '';
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

[
'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
'text' => 'V.Unit',
],

[
'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
'text' => 'P.Unit',
],

[
'class_name' => 'bg-black c-white pr-x3 pl-x3 text-right',
'text' => 'Importe',
],

];

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
        <td class="border-left-style-solid pl-x3 vertical-align-top">{{ $item->DetItem }}</td>
        <td class="pl-x6 vertical-align-top">{{ $item->DetCodi }}</td>
        <td class="pl-x6 text-left vertical-align-top">{{ $item->DetNomb }}
          @if( $item->DetDeta )
          <br> <span class="font-style:italic"></span> {!! $item->DetDetaFormat() !!}
          @endif
        </td>
        <td class="pr-x6 vertical-align-top">{{ $item->DetUnid }}</td>
        <td class="text-right pr-x3 vertical-align-top">{{ fixedValue($item->DetPeso) }}</td>
        <td class="text-right border-right-style-solid border-left-style-solid pr-x3 pl-x3 vertical-align-top">{{ $item->DetCant }}</td>
        <td class="text-right border-right-style-solid pr-x3 pl-x3 vertical-align-top"> {{ decimal($item->valorUnitario(), $decimals ) }}</td>
        <td class="text-right border-right-style-solid pr-x3 pl-x3 vertical-align-top"> {{ decimal($item->precioUnitario(), $decimals ) }}</td>
        <td class="text-right border-right-style-solid pr-x3 pl-x3 vertical-align-top"> {{ decimal( $item->DetImpo,  $decimals ) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>