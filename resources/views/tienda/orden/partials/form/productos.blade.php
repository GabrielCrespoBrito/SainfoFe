<div class='section-info productos'>

  <div class='title-info'> <span class="fa fa-cubes"></span> Productos </div>

  @component('components.table' , ['thead' => ['#' , 'Codigo', 'Nombre' , 'Cantidad' ] , 'id' => "table-orden-productos"])
    
    @slot('body')
      @foreach( $data['productos'] as $product )
        <tr>
          <td>  {{ $product['item'] }}  </td>
          <td>  {{ $product['codigo'] }}  </td>
          <td>  {{ $product['nombre'] }}  </td>
          <td>  {{ $product['cantidad'] }}  </td>
        </tr>
      @endforeach
    @endslot

  @slot('tfoot')
    <tr>
      <td colspan="3"> <strong> Total </strong>  </td>
      <td> {{ $data['total_cantidad'] }}  </td>
    </tr>
  @endslot
@endcomponent

</diV>
