
 @component('components.table', [ 'id' => 'datatable' , 'class_name' => 'sainfo-noicon size-9em', 'thead' => 
 [ 'Nº Cotización' , 'Cliente' , 'Documento' , 'Fecha Creación', 'Productos',   ''] ])
  @slot('body')


    @if( count($data['orders']))

      @foreach( $data['orders'] as $order )
        <tr>
          <td> <a data-target-container="#order-show-container" data-info="{{ json_encode($order) }}" class="btn btn-default btn-xs btn-flat btn-show-order" href="#"> <span class="fa fa-file-text-o"></span> {{ $order['id'] }}  </a> </td>
          <td> {{ $order['cliente']['razon_social'] }} </td>
          <td> {{ $order['cliente']['documento'] }} </td>
          <td> {{ $order['created_at'] }}  </td>
          <td> {{ $order['items_count'] }} </td>
          <td> 
            @if($importMode)
              <a class="btn btn-xs btn-default" href="{{ route('coti.create', [ 'tipo' => 50,  'importar' => $order['id']]) }}"> Generar Cotización </a>
            @else

            <div class="dropdown">
              <button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Acciones  <span class="caret"></span>
              </button>
              <ul class="dropdown-menu sainfo">
                <li><a class="" href="{{ route('coti.create', [ 'tipo' => 50,  'importar' => $order['id']]) }}"> Generar Cotización </a></li>
                
                <li>
                  <a class="" href="{{ route('tienda.destroy', $order['id'] ) }}"> Eliminar </a>
                </li>

              </ul>
            </div>
            @endif

          </td>
        </tr>
      @endforeach

    @else

      <tr>
        <td colspan="7"> No Hay Cotizaciones </td>
      </tr>

    @endif

  @endslot
  @endcomponent