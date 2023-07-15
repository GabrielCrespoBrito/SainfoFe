@php

$routeNameShow = $isAdmin ? 'suscripcion.ordenes.show.admin' : 'suscripcion.ordenes.show';

$thead = 
  $isAdmin ?
    [ 'Orden' , 'Fecha', 'Plan', 'Empresa', 'Usuario', 'Total', 'Estado' ] :
    [ 'Orden' , 'Fecha', 'Plan', 'Total', 'Estado' ];
    
@endphp

@component('components.table', [ 'class_name' => 'ordenes_pago_table', 'id' => 'idTable','thead' => $thead ])
  @slot('body')
    @foreach($ordenes as $orden)
      <tr class="{{ $orden->estatus }}">
        <td> <a href="{{  route($routeNameShow , $orden->id ) }}"> {{ $orden->getIdFormat() }} </a> </td>
        <td> {{ $orden->fecha_emision }} </td>
        <td> {{ $orden->planduracion->nombreCompleto()  }} </td>
        @if($isAdmin)
        <td> {{ optional($orden->empresa)->nombre() }} </td>
        <td> {{ $orden->user->nombre() }} </td>
        @endif
        <td> {{ $orden->total }} </td>
        <td> {{ $orden->estatus }} </td>
      </tr>
    @endforeach
  @endslot
@endcomponent
