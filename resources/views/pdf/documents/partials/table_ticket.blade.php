
@php
$class_name = $class_name ?? '';
$class_name_table = $class_name_table ?? '';
$thead = $thead ?? '';
$tbody = $tbody ?? '';
$campoPrecioName = $orden_campos['precio_unitario'] ? 'P.Unit' : 'V.Unit';
$line_cut = $line_cut ?? false;


@endphp


<div class="items {{ $class_name }}">
  <table width="100%" class="table_items_class {{ $class_name_table }}">
    <thead>
      <tr class="tr_head bold {{ $thead }}">
        <td width="4%" class="font-size-9 p-0" style="padding-right: 0px">Unid</td>
        <td colspan="2" width="96%" class="pr-x3 font-size-9">Descripción </td>
      </tr>
      <tr class="tr_head bold {{ $thead }}">
        <td class="border-bottom-style-dotted border-width-1 pr-x9 text-left font-size-9" style="margin:0; padding:0; padding-right: 4px"> Cant </td>
        <td class="border-bottom-style-dotted border-width-1 pr-x3 text-right font-size-9" style="margin:0; padding:0"> {{ $campoPrecioName }}  </td>
        <td class="border-bottom-style-dotted border-width-1 pr-x3 text-right font-size-9" style="margin:0; padding:0"> Importe  </td>
      </tr>
    </thead>
    <tbody class="{{ $tbody }}">
      @foreach( $items as $item )
      @php
        $valuePrecioName = decimal( $item->getPrecio($orden_campos['precio_unitario']), $decimals );
      @endphp

      <tr>
        <td class="font-size-9">{{ $item->DetUnid }}</td>
        <td colspan="2" class="font-size-9">
        {{ substr(removeWhiteSpace($item->DetNomb), 0, $line_cut ? 35 : strlen($item->DetNomb)) }}
          @if( $item->DetDeta ) <br>{!! $item->DetDetaFormat() !!} @endif
        </td>
      </tr>
      <tr>
        <td class="border-bottom-style-dotted border-width-1 font-size-9">{{ $item->DetCant }}</td>
        <td class="border-bottom-style-dotted border-width-1 text-right font-size-9">{{ $valuePrecioName }}</td>
        <td class="border-bottom-style-dotted border-width-1 text-right font-size-9">{{ decimal($item->DetImpo) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>