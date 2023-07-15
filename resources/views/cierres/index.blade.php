@view_data([
'layout' => 'layouts.master' ,
'title' => 'Cierre de Mes',
'titulo_pagina' => 'Cierre de Mes',
'bread' => [ ['Cierre de Mes'] ],
'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','cierre/index.js']]
])

@slot('contenido')

@push('js')
@include('partials.errores')
@endpush


@component('components.table', [ 'id' => 'datatable' , 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Mes' , 'Estado' , 'Usuario' , 'Fecha', ''] ])

@slot('body')
@foreach( $meses as $mes )
@php
$exists = $mes->cierre->hasFechaCerrado();
@endphp
<tr>

  <td>
    {{ $mes->mesnomb }}
  </td>

  <td>
    @if( $exists )
    <span class="btn-sainfo default"> <span class="fa fa-lock"></span> Cerrado </span>
    @else
    <span class="btn-sainfo green"> <span class="fa fa-unlock"></span> Abierto </span>
    @endif
  </td>

  <td>
    {{ $mes->cierre->usucodi }}
  </td>

  <td>
    {{ $mes->cierre->fecha_cierre }}
  </td>

  <td>
    @if( $exists )
    <a class="btn-sainfo green" href="{{ route('cierre.toggle', $mes->mescodi) }}"> <span class="fa fa-unlock"></span> Aperturar </a>
    @else
    <a class="btn-sainfo default" style="color:black" href="{{ route('cierre.toggle', $mes->mescodi) }}"> <span class="fa fa-unlock"></span> Cerrar </a>
    @endif
  </td>

</tr>
@endforeach
@endslot

@endcomponent

@endslot

@endview_data