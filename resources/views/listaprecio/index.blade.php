@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Listas de Precios',
  'titulo_pagina'  => 'Listas de Precios', 
  'bread'  => [ ['Listas de precios',  route('listaprecio.index') ] ],
  'assets' => ['js' => ['helpers.js']]
])


{{-- @include('partials.errores') --}}

@slot('contenido')

  <div class="row">
    <div class="col-md-12">
      <a href="{{ route('listaprecio.create') }}" class="btn btn-primary btn-flat pull-right"> <span class="fa fa-plus"></span> Nuevo </a>
    </div>
  </div>

  @component('components.table', ['thead' => [ 'Codigo' , 'Nombre' , 'Local', 'Acciones'] ])
  	@slot('body')
  	@foreach( $listaprecios as $listaprecio )
  		<tr>
  			<td>{{ $listaprecio->LisCodi }}</td>
  			<td>{{ $listaprecio->LisNomb }}</td>
  			<td>{{ $listaprecio->local->LocNomb }} </td>
  			<td>
  				<a href="{{ route('listaprecio.edit', $listaprecio->LisCodi) }}" class="btn btn-default btn-xs"> Modificar </a>
  				<a href="#"onclick="event.preventDefault();document.getElementById('delete{{ $loop->index }}').submit();" class="btn btn-default btn-xs"> Eliminar </a>
          <form id="delete{{ $loop->index }}" method="post" action="{{ route('listaprecio.destroy' , $listaprecio->LisCodi) }}">
            @csrf
            @method('DELETE')
          </form>
  			</td>
  		</tr>
  	@endforeach
  	@endslot
  @endcomponent

@endslot  

@endview_data