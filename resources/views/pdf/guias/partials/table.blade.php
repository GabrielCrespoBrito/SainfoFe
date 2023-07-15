@php


$complete_tds = $complete_tds ?? false;
$cant_tds = $complete_tds ? (($cant_tds ?? 20) - count($items)) : false;
$class_name = $class_name ?? '';
$class_name_table = $class_name_table ?? '';
$thead_class = $thead_class ?? '';
$tbody_class = $tbody_class ?? '';
$class_codigo = $class_codigo ?? '';
$class_unidad = $class_unidad ?? '';
$class_descripcion = $class_descripcion ?? '';
$class_cant = $class_cant ?? '';
$class_peso = $class_peso ?? '';

$theads = [
  [
  'class_name' => '',
  'text' => 'Codigo',
  ],
  [
  'class_name' => '',
  'text' => 'Cant.',
  ],

  [
  'class_name' => '',
  'text' => 'Unidad',
  ],
  [
  'class_name' => '',
  'text' => 'Descripción',
  ],

  [
  'class_name' => 'border-right-style-solid text-right pr-x5',
  'text' => 'Peso',
  ],
]
@endphp


<div class="items {{ $class_name }}">
  <table class="table_items_class {{ $class_name_table }}">
    <thead>
      <tr class="tr_head">
        @foreach( $theads as $td )
          <td class="{{ $td['class_name'] }} {{ $thead_class }}"> {{ $td['text'] }} </td>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach( $items as $item )
        <tr>
          <td class="{{ $class_codigo }} {{ $tbody_class }}"> {{ $item->DetCodi }} </td>
          <td class="{{ $class_cant }} {{ $tbody_class }}"> {{ $item->Detcant }} </td>
          <td class="{{ $class_unidad }} {{ $tbody_class }}"> {{ $item->DetUnid }} </td>
          <td class="{{ $class_descripcion }} {{ $tbody_class }}"> {{ $item->DetNomb }} {{ $item->DetDeta }} </td>
          <td style="text-align:right" class="{{ $class_peso }} {{ $tbody_class }}"> {{ $item->DetPeso }} </td>
        </tr>
      @endforeach 

      @if( $complete_tds )
      @for( $i = 0; $i < $cant_tds; $i++ )
        <tr>
          <td class="{{ $class_codigo }} {{ $tbody_class }}"> &nbsp; </td>
          <td class="{{ $class_cant }} {{ $tbody_class }}"> &nbsp;  </td>
          <td class="{{ $class_unidad }} {{ $tbody_class }}"> &nbsp; </td>
          <td class="{{ $class_descripcion }} {{ $tbody_class }}">  &nbsp; </td>
          <td class="{{ $class_peso }} {{ $tbody_class }}">  &nbsp; </td>
        </tr>      
      @endfor
      @endif

    </tbody> 
  </table>
</div>