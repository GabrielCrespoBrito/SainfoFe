@php
  $thead = [ 'Documento', 'Imp (S./)', 'Cost. (S./)', 'Utilidad (.S/)', [ 'attributes' => [ 'style' => 'text-align:left;padding-left:20px' ], 'class_name' => 'text-align-left', 'text' => 'Razòn Social'] ];


  $tableInHtml  ? array_push( $thead , 'Mostrar' ) : null;
  !$tableInHtml ? array_unshift( $thead , 'Fecha' ) : null;
  $colspan = 7;
  $class_name = $class_name ?? '';
  $class_name .= ' table_items table-utilidades utilidad-venta';
@endphp

@component('components.table', ['thead' => $thead, 'id' => '', 'class_name' => $class_name, 'container_class' => 'pl-0 pr-0' ])

@slot('body')

@foreach( $data['items'] as $data_venta )

  @if( $data_venta['total']['venta_soles'] == 0 )
    {{-- @continue --}}
  @endif

  <tr class="tr-venta {{ $data_venta['info']['positive'] ? 'is-positive' : 'is-negative' }}">

    @if(!$tableInHtml)
    <td>{{ $fecha }}</td>
    @endif
    <td> 
      @if($tableInHtml)
        <a target="_blank" href="{{ $data_venta['info']['route']  }}"> {{ $data_venta['info']['data'] }} </a>
      @else
        {{ $data_venta['info']['data'] }} 
      @endif
    </td>

    <td>{{ decimal($data_venta['total']['venta_soles']) }}</td>
    <td>{{ decimal($data_venta['total']['costo_soles']) }}</td>
    <td class="utilidad">{{ decimal($data_venta['total']['utilidad_soles']) }}</td>
    <td style="text-align:left; padding-left: 20px;">{{ $data_venta['info']['cliente']  }}</td>
    @if($tableInHtml)
    <td> <a href="#" class="btn btn-default btn-xs btn-flat span-info"> <span class="fa fa-eye"></span></a></td>
    @endif
  </tr>

  @if($tableInHtml)
  <tr class="tr-dias tr-container-info" style="display:none">
    <td colspan="{{ $colspan }}" class="pl-0 pr-0" style="padding-left:0!important;padding-right:0!important">
      @include('reportes.ganancias.partials.table_items', ['data' => $data_venta ])
    </td>
  </tr>
  @endif

@endforeach

@endslot

@slot('tfoot')
  <tr class="tr-total {{ $data_fecha['info']['positive'] ? 'is-positive' : 'is-negative' }}">
    @if(!$tableInHtml)
      <td class="total"></td>
      @endif
      <td class="total">Total </td>
      <td class="total">{{ decimal($data['total']['venta_soles']) }}</td>
      <td class="total">{{ decimal($data['total']['costo_soles']) }}</td>
      <td class="utilidad total">{{ decimal($data['total']['utilidad_soles']) }}</td>
      <td class="total"></td>
      @if($tableInHtml)
      <td class="total"></td>
      @endif
  </tr>
  @endslot

@endcomponent