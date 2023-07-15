@php 
  $thead = [ 'Base' , 'Codigo' , 'Unidad' , 'Descripcion' , 'Precio' , 'Cantidad' , 'Importe' ];
  $colspan = 8;
@endphp

@component('components.table', [ 'thead' => $thead, 'id' => '', 'class_name' => 'table-utilidades utilidad-item', 'container_class' => 'pl-0 pr-0' ])
  @slot('body')
  @foreach( $data['items'] as $data_item )

  <tr data-id="{{ (bool) $data_item['info']['positive'] }}" class="tr-item {{ $data_item['info']['positive'] ? 'is-positive' : 'is-negative' }}">
      <td>{{ $data_item['info']['base']  }} </td>
      <td>{{ $data_item['info']['codigo']  }} </td>
      <td>{{ $data_item['info']['unidad']  }} </td>
      <td>{{ $data_item['info']['descripcion']  }} </td>
      <td>{{ $data_item['info']['precio']  }} </td>
      <td>{{ $data_item['info']['cantidad'] }} </td>
      <td class="utilidad total">{{ $data_item['info']['importe']  }} </td>
    </tr>
  @endforeach
  @endslot
@endcomponent