@php
  $thead = [ 
    'Item',
    'Codigo',
    'Unidad',
    'Descripción', 
    ['text' => 'Cant', 'class_name' => 'text-right-i' ], ['text' => 'Precio', 'class_name' => 'text-right-i'] ,
    ['text' => 'Desct1', 'class_name' => 'text-right-i'],
    ['text' => 'Desct2', 'class_name' => 'text-right-i'],
    ['text' => 'Importe','class_name' => 'text-right-i']
    ];
  
  if( $active_form ){
    array_unshift( $thead , [ 'text' => 'A.C *', 'attributes' => [ 'title' => 'Actualizar Costos del Producto'], 'class_name' => 'text'  ]);
    array_push( $thead , "" );
  }
@endphp


@component('components.table' , ['thead' => $thead , 'id' => "items-table" , 'class_name' => 'sainfo compras' ])

@slot('body')
    @foreach( $compra->items as $item )
      @php
        $item = $item->toArray();
        $item['producto']['profoto'] = '';
      @endphp
      <tr data-info="{{ json_encode($item) }}">
      @if($active_form)
        <td> <input type="checkbox" class="update_costo" value="1"> </td>
      @endif
        <td>  {{ $item['DetItem'] }}  </td>
        <td>  {{ $item['Detcodi'] }}  </td>
        <td>  {{ $item['DetUnid'] }}  </td>
        <td>  {{ $item['Detnomb'] }}  </td>
        <td class="td-number">  {{ $item['DetCant'] }}  </td>
        <td class="td-number">  {{ $item['DetPrec'] }}  </td>
        <td class="td-number">  {{ $item['DetDct1'] }}  </td>
        <td class="td-number">  {{ $item['DetDct2'] }}  </td>
        <td class="td-number">  {{ $item['DetImpo'] }}  </td>
        @if($active_form)
        <td>  
          <a href="#" class="btn btn-xs modificar_item btn-primary"> <span class="fa fa-pencil"></span> </a>
          <a href="#" class="btn btn-xs eliminar_item btn-danger"> <span class="fa fa-trash"></span> </a>
        </td>
        @endif        
      </tr>
    @endforeach
  @endslot
  
@endcomponent