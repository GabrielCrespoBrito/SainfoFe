@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Formas de pago',
  'titulo_pagina'  => 'Formas de pago', 
  'bread'  => [ ['Formas de pago'] ],
  'assets' => ['js' => ['helpers.js','forma_pago/index.js']]
])

@slot('contenido')

  @push('js') 
    @include('partials.errores')
  @endpush 

  <div class="row">
    <div class="col-md-12">
      <a class="btn btn-primary pull-right btn-flat" href="{{ route('formas-pago.create') }}"> Nueva </a>
    </div>
  </div>

  @component('components.table', ['class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Nombre' , 'Diás' , ''] ])
    @slot('body')
      @foreach( $fps as $fp )
        <tr>
          <td> {{ $fp->connomb }} </td>
          <td> {{ $fp->condias }} </td>
          <td> 
            <a title="Modificar" class="btn btn-xs btn-default" href="{{ route('formas-pago.edit', $fp->conCodi) }}"> <span class="fa fa-pencil"></span> </a> 
            <a title="Eliminar" data-id="{{ $fp->id }}" class="btn btn-xs btn-danger delete-btn" href="#"> <span class="fa fa-trash"></span> </a> 
          </td>
        </tr>
      @endforeach
    @endslot
  @endcomponent

  <form action="" class="form-delete-forma-pago" style="display:none" method="post" data-route="{{ route('formas-pago.destroy', '@@') }}"> 
    @csrf
	  @method('DELETE')
  </form>

@endslot  

@endview_data

