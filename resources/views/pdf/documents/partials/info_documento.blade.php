@php
  $class_name = $class_name ?? '';
  $class_name_table = $class_name_table ?? '';
  $valor_td_class = $valor_td_class ?? '';
  $titulo_td_class = $titulo_td_class ?? '';
  $colspan = $colspan ?? '';
  $rowspan = $rowspan ?? '';
@endphp

<div class="table_info_documento {{ $class_name }}">
  <table class="{{ $class_name_table }}">
    @foreach( $trs as $tds )
    <tr class="tr_head">
      
      @foreach( $tds as $td )
        @php
          $class_name = $td['class_name'] ?? $class_name;

          $titulo_td_class = $td['titulo_td_class'] ?? $titulo_td_class;
          $titulo_text = $td['titulo_text'] ?? '';

          $valor_td_class = $td['valor_td_class'] ?? $valor_td_class;
          $valor_text = $td['valor_text'] ?? '';

          $colspan = $td['colspan'] ?? $colspan;
          $rowspan = $td['rowspan'] ?? $rowspan;
        @endphp
        
        <td
        style=""
        colspan="{{ $colspan }}"
        rowspan="{{ $rowspan }}"
        class="{{ $class_name }}">
          <div class="{{ $titulo_td_class }}"> {!! $titulo_text !!}</div>
          <div class="{{ $valor_td_class }}"> {!! $valor_text !!}</div>
        </td>

      @endforeach
      
    </tr>
    @endforeach
  </table>
</div>
