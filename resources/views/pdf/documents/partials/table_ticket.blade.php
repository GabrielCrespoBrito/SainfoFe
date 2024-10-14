
@php
$class_name = $class_name ?? '';
$class_name_table = $class_name_table ?? '';
$thead = $thead ?? '';
$tbody = $tbody ?? '';
$campoPrecioName = $orden_campos['precio_unitario'] ? 'P.Unit' : 'V.Unit';



@endphp


<div class="items {{ $class_name }}">
  <table width="100%" class="table_items_class {{ $class_name_table }}">
    <thead>
      <tr class="tr_head bold {{ $thead }}">
        <td width="20%" class="pr-x9">Unid</td>
        <td colspan="2" class="pr-x3">Descripciòn </td>
      </tr>
      <tr class="tr_head bold {{ $thead }}">
        <td class="border-bottom-style-dotted border-width-1 pr-x9 text-left"> Cant. </td>
        <td class="border-bottom-style-dotted border-width-1 pr-x3 text-right"> {{ $campoPrecioName }}  </td>
        <td class="border-bottom-style-dotted border-width-1 pr-x3 text-right"> Importe  </td>
      </tr>
    </thead>
    <tbody class="{{ $tbody }}">
      @foreach( $items as $item )
      @php
        $valuePrecioName = decimal( $item->getPrecio($orden_campos['precio_unitario']), $decimals );
      @endphp

      <tr>
        <td class="">{{ $item->DetUnid }}</td>
        <td colspan="2" class="">{{ removeWhiteSpace($item->DetNomb) }}
          @if( $item->DetDeta ) <br>{!! $item->DetDetaFormat() !!} @endif
        </td>
      </tr>
      <tr>
        <td class="border-bottom-style-dotted border-width-1">{{ $item->DetCant }}</td>
        <td class="border-bottom-style-dotted border-width-1 text-right">{{ $valuePrecioName }}</td>
        <td class="border-bottom-style-dotted border-width-1 text-right">{{ decimal($item->DetImpo) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>