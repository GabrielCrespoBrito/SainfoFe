@php
$tableInHtml = $tableInHtml ?? false;
$thead = [ 'Fecha', 'Imp (S./)', 'Cost. (S./)']; 
$descontarPorcVendedor ? array_push( $thead , 'Comision' ) : $thead;
$thead[] = 'Utilidad (.S/)';

$tableInHtml ? array_push( $thead , 'Mostrar' ) : $thead;

$colspan = 4; 
$colspan = $tableInHtml ? ++$colspan : $colspan;
$colspan = $descontarPorcVendedor ? ++$colspan : $colspan; 

$class_table = $class_table ?? '';
$class_table .= ' table-utilidades utilidad-fecha';
@endphp

@component('components.table', ['thead' => $thead, 'id' => '', 'class_name' => $class_table  ])

  @slot('body')

  @foreach( $data['items'] as $fecha => $data_fecha )

  @if( $data_fecha['total']['venta_soles'] == 0 && $data_fecha['total']['venta_dolar'] == 0 )
    @continue
  @endif

  <tr class="tr-dias {{ $data_fecha['info']['positive'] ? 'is-positive' : 'is-negative' }}">
    <td class="text-right">{{ $fecha }}</td>
    <td class="text-right">{{ decimal($data_fecha['total']['venta_soles']) }}</td>
    <td class="text-right">{{ decimal($data_fecha['total']['costo_soles']) }}</td>
    @if($descontarPorcVendedor)
    <td class="text-right">{{ decimal($data_fecha['total']['costo_soles_por_vendedor']) }}</td>
    @endif

    <td class="text-right utilidad">{{ decimal($data_fecha['total']['utilidad_soles']) }}</td>


    @if($tableInHtml)
      <td class="text-right"> 
        <a target="_blank"  href="#" class="btn btn-default btn-xs btn-flat span-info"> <span class="fa fa-eye"></span></a>
        <a target="_blank" href="{{ route('reportes.utilidades.pdf_fecha', ['fecha' => $fecha , 'local' => $local, 'grupo' => $grupo,'vendedor' => $vendedor, 'descontarPorcVendedor' => $descontarPorcVendedor ]) }}" class="btn btn-default btn-xs btn-flat"> <span class="fa fa-file-pdf-o" title="Reporte de los documentos del dia"></span></a>
      </td>
    @endif
  </tr>

  @if($tableInHtml)
  <tr class="tr-ventas tr-container-info" style="display:none">
    <td colspan="{{ $colspan }}" class="pl-0 pr-0 text-right" style="padding-left:0!important;padding-right:0!important">
      @include('reportes.ganancias.partials.table_venta', ['data' => $data_fecha, 'tableInHtml' => $tableInHtml ])
    </td>
  </tr>
  @endif

  @endforeach
  @endslot

  @slot('tfoot')
    <tr class="tr-total {{ $data['info']['positive'] ? 'is-positive' : 'is-negative' }}">
      <td class="total"> Total </td>
      <td class="total">{{ decimal($data['total']['venta_soles']) }}</td>
      <td class="total">{{ decimal($data['total']['costo_soles']) }}</td>
      @if($descontarPorcVendedor)
      <td class="total">{{ decimal($data['total']['costo_soles_por_vendedor']) }}</td>
      @endif
      <td class="utilidad total">{{ decimal($data['total']['utilidad_soles']) }}</td>
      @if($tableInHtml)
        <td class="total"></td>
      @endif
    </tr>
  @endslot

@endcomponent