@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Locales',
  'titulo_pagina'  => 'Locales', 
  'bread'  => [ ['Locales'] ],
  'assets' => ['js' => ['helpers.js','locales/index.js']]
])

@slot('contenido')

  @push('js') 
    @include('partials.errores')
  @endpush 

  <div class="row">
    <div class="col-md-12">
      <a class="btn btn-primary pull-right btn-flat" href="{{ route('locales.create') }}"> Nueva </a>
    </div>
  </div>

  @component('components.table', ['class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Nombre' , 'Direcciòn' , 'Telefono', ''] ])
    @slot('body')
      @foreach( $locales as $local )
        <tr>
          <td> {{ $local->LocNomb }} </td>
          <td> {{ $local->LocDire }} </td>
          <td> {{ $local->LocTele }} </td>
          <td>
            <a title="Modificar" class="btn btn-xs btn-default" href="{{ route('locales.edit', $local->LocCodi) }}"> <span class="fa fa-pencil"></span> </a>
            <a title="Eliminar" data-id="{{ $local->LocCodi }}" class="btn btn-xs btn-danger delete-btn" href="#"> <span class="fa fa-trash"></span> </a>

          </td>
        </tr>
      @endforeach
    @endslot
  @endcomponent

  <form action="" class="form-delete-locales" style="display:none" method="post" data-route="{{ route('locales.destroy', '@@') }}">

    @csrf
	  @method('DELETE')
  </form>

@endslot  

@endview_data

