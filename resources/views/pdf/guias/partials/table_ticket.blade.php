
@php
$class_name = $class_name ?? '';
$class_name_table = $class_name_table ?? '';
@endphp

<div class="items {{ $class_name }}">
  <table width="100%" class="table_items_class {{ $class_name_table }}">
    <thead>
      <tr class="tr_head bold">
        <td width="30%" class="pr-x9">COD.</td>
        <td colspan="2" class="pr-x3">DESCRIPCION </td>
      </tr>
      <tr class="tr_head bold">
        <td class="border-bottom-style-dotted border-width-1 pr-x9 text-left">CANT.</td>
        <td class="border-bottom-style-dotted border-width-1 pr-x3 text-left">UNIDAD </td>
        <td class="border-bottom-style-dotted border-width-1 pr-x3 text-right">PESO </td>
      </tr>
    </thead>
    <tbody>
      @foreach( $items as $item )
      <tr>
        <td>{{ $item->DetCodi }}</td>
        <td colspan="2">{{ removeWhiteSpace($item->DetNomb) }}</td>
      </tr>
      <tr>
        <td class="border-bottom-style-dotted border-width-1 text-left">{{ $item->Detcant }}</td>
        <td class="border-bottom-style-dotted border-width-1 text-left">{{ $item->DetUnid }}</td>
        <td class="border-bottom-style-dotted border-width-1 text-right">{{ $item->DetPeso }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>