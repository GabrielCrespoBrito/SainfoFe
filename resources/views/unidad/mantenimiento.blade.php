@extends('layouts.master')

@section('js')
  <script type="text/javascript">
    var producto = {
      peso : {{ $producto->ProPeso }},
      costo_dolar : {{ $producto->ProPUCD }},
      costo_sol : {{ $producto->ProPUCS }},
      margen : {{ $producto->ProMarg }},
      venta_dolar : {{ $producto->ProPUVD }},
      venta_sol : {{ $producto->ProPUVS }},
    }
  </script>  
  <script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
  <script src="{{ asset('js/unidad/script.js') }}"> </script>

  @include('partials.errores')

@endsection

@section('titulo_pagina', 'Menudeo')

@section('bread')
  <li> <a href="{{ route('productos.index') }}"> Productos </a> </li>
  <li> <a href="{{ route('productos.unidad.mantenimiento', $producto->ID ) }}"> {{ $producto->ProNomb }} </a> </li>
  <li>Menudeo</li>
@endsection

@section('contenido')

<div class="acciones-div">

<div class="row">
  <div class="col-md-6">  
    <span class="name_producto">
      <span class="codigo"> {{ $producto->ProCodi }} </span>
      <span class="unidad"> {{ $producto->unpcodi }} </span>  
      <span class="nombre"> {{ $producto->ProNomb }} </span>
    </span>
  </div>
  @if($create)
  <div class="col-md-6">  
    <a href="#" class="btn pull-right btn-flat show_form btn-default"> <span class="fa fa-plus"></span> Nueva  </a>
  @endif
  </div>
</div>


<div class="row form_create" style="display: {{ $create && $errors->count() == 0  ? 'none' : 'block' }}">
  <div class="col-md-12">
    @include('unidad.partials.form',['unidad' => $unidad])
  </div>
</div>

</div>

<div class="col-md-12" style="overflow-x: scroll;">
  <table class="table sainfo-table " id="datatable" >
  <thead>
  	<tr>
      <td> Código </td>
  		<td> Lista </td>
      <td> Entero </td>
      <td> Unidad </td>
      <td> Medida </td>
      <td> Unidad </td>
      <td> Peso </td>
      <td> Costo US$ </td>
      <td> Costo S/ </td>
      <td> % Marg. </td>
      <td> Venta US$ </td>
      <td> Venta S/ </td>      
      <td> Acciones </td>   
  	</tr>
  </thead>  
  <tbody>
    @foreach( $producto->unidades as $uni )
      @php
        $className = '';
        if( !$create ){
          $className = $unidad->Unicodi == $uni->Unicodi ? 'seleccionado' : '' ;
        }
      @endphp

    <tr class="{{ $className }}">
      <td> {{ $uni->Unicodi }} </td>
      <td> {{ $uni->lista->LisNomb   }} </td>
      <td> {{ $uni->UniEnte   }} </td>
      <td> {{ $producto->unpcodi }} </td>      
      <td> {{ $uni->UniMedi   }} </td>
      <td> {{ $uni->UniAbre   }} </td>
      <td> {{ $uni->UniPeso   }} </td>
      <td> {{ $uni->UniPUCD   }} </td>
      <td> {{ $uni->UniPUCS   }} </td>
      <td> {{ $uni->UniMarg   }} </td>
      <td> {{ $uni->UNIPUVD   }} </td>
      <td> {{ $uni->UNIPUVS   }} </td>
      <td>
        <div class="dropdown">
          <button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> Acciones  <span class="caret"></span> </button>
          
          <ul class="dropdown-menu">
              <li> <a class="#" href="{{route('unidad.edit', $uni->Unicodi ) }}"> Editar </a> </li>
              @can('delete-unidad',$unidad)
              <li> 
                <a 
                class="#"
                onclick="event.preventDefault(); document.getElementById('e{{$loop->index}}').submit();" 
                href="#"> Eliminar </a>

                <form id="e{{ $loop->index }}" style="display:none" method="post" action="{{ route('unidad.delete', $uni->Unicodi ) }}">
                    @csrf @method('DELETE')
                </form>
              </li>
              @endcan          
          </ul>
        </div>
      </td> 
    </tr>
    @endforeach
  </tbody>
  </table>
</div>

@endsection