@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Vendedores',
  'titulo_pagina'  => 'Vendedores', 
  'bread'  => [ ['Vendedores'] ],
  'assets' => [ 'libs' => ['datatable'], 'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')

  <div class="row">

    <div class="col-md-4"> 
    
        <label> <input type="checkbox" {{ $delete ? 'checked=checked' : '' }}  data-url="{{$route}}" name="deleted_input" value="1"> Mostrar eliminados </label>
      
    </div>

    <div class="col-md-8"> <a class="btn btn-primary btn-flat pull-right" href="{{ route('vendedor.create') }}"> Nueva </a>
    </div>
  </div>

  @include('partials.errors_html')

  @component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ '#' , 'Nombre' , 'Telefono' , 'Email', 'Usuario',  'Acciones'] 
  ])
  @slot('body')
    @foreach ($vendedores as $vendedor)
      <tr>
        <td> {{ $vendedor->Vencodi }} </td>
        <td> {{ $vendedor->vennomb }} </td>
        <td> {{ $vendedor->ventel1 }} </td>
        <td> {{ $vendedor->venmail }} </td>
        <td> {{ $vendedor->getUserLogin() }} </td>
        <td>
        @php

          $routeDelete = route('vendedor.destroy', $vendedor->Vencodi);
          $routeText = 'Eliminar';

        if($vendedor->isDelete()){
          $routeDelete = route('vendedor.restaurar', $vendedor->Vencodi);
          $routeText = 'Restaurar';
        }

          $links = [
            ['src' => route('vendedor.edit', $vendedor->Vencodi),'texto' => 'Modificar'],
            ['src' => $routeDelete, 'texto' => $routeText ],
          ];
      @endphp

      @include('partials.column_accion', [ 'links' => $links ])
        </td>
      </tr>
    @endforeach
  @endslot
  @endcomponent

  @include('partials.modal_eliminate', ['url' => route('vendedor.destroy' , 'XX') ])
  
@endslot
@endview_data