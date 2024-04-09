@php
  $routeAction = $unread ? 
    route('admin.notificaciones.read_massive') :
    route('admin.notificaciones.unread_massive');

  $theads = [
    "<input type='checkbox' class='mask-all'>",
    'Titulo',
    'Descripcion',
    'Fecha Not.',
  ];

  if( ! $unread ){
    array_push( $theads, 'Fecha Lec.');
  }

@endphp


@if(!$notificaciones_data->count)
<div class="row">
  <div class="col-md-12">
    <div class="no-notificacion notificacion-{{ (int) $unread }}"> NO HAY NOTIFICACIONES {{ $unread ? 'PENDIENTES' : 'LEIDAS' }} </div>
  </div>
</div>

@else

<div class="filtros">

  <a class="btn btn-flat btn-default  disabled action-massive mark-read" data-url="{{ $routeAction }}" href="#"><span class="fa fa-check"></span> Marca Como Leidas </a>

  <a class="btn btn-flat btn-danger  disabled action-massive delete-action" data-url="{{ route('admin.notificaciones.delete_massive') }}" href="#">
  <span class="fa fa-trash"></span> Eliminar </a>

</div>


<div class="col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">
  <table 
    style="width: 100% !important;"
    class="table sainfo-table sainfo-noicon notificaciones oneline pt-0">
    <thead>
      <tr>
        @foreach($theads as $thead)
          <td> {!! $thead !!} </td>
        @endforeach
      </tr>
    </thead>
        <tbody>
            @foreach( $notificaciones_data->items as $item )            
            {{-- @dump( $item ) --}}            
            <tr class="bg-{{ $item['type'] }}">
              <td>
                <input
                  type="checkbox"
                  class="mark-input"
                  value="{{ $item['id'] }}"
                  name="notification-read[]">
              </td>
              <td> <a style="color:black" href="{{ route('admin.notificaciones.show', $item['id']) }}" target="_blank"> {{ $item['titulo'] }} </a></td>
              @php
                logger($item);
              @endphp
              <td> 
                @if( is_array($item['descripcion']) )
                -
                  {{-- <a href="{!! $item['route'] !!}"> Ver </a> --}}
                  {{-- http://20603419287.localhost:8004/admin/notificaciones/76187094-ab50-4829-8994-b5ddbe8f5965 --}}
                @else
                  {!! $item['descripcion'] !!}
                @endif
              </td>
              <td> {{ $item['date'] }} </td>
              @if( !$unread )
              <td> {{ $item['date_read'] }} </td>
              @endif
            </tr>           
            @endforeach
        </tbody>



  </table>
</div>

@endif

