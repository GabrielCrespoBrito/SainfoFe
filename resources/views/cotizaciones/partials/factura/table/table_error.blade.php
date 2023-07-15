@php
  $cantProductos = count($productosError);
  $message = "Los siguientes {$cantProductos} productos no se pudieron agregar a la cotización porque su codigo no corresponde con ningun producto del sistema, por favor verifique";
@endphp

<div class="col-md-12- col-xs-12  table-orden-error">

  <div class="title-error"> <span class="fa fa-exclamation-circle"> </span> {{ $message }}  </div>
  @component('components.table' , ['thead' => ['Codigo' , 'Descripción' , 'Cantidad'] , 'id' => "table-orden-error" , 'class_name' => 'sainfo' ])
    @slot('body')
      @foreach( $productosError as $item )
      {{-- @dd( $item ) --}}
      <tr>
        <td>  {{ $item->producto->code }}  </td>
        <td>  {{ $item->producto->nombre }}  </td>
        <td>  {{ $item->product_qty }}  </td>
      </tr>
      @endforeach
    @endslot
  @endcomponent
  
</div>
