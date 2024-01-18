@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Zonas',
  'titulo_pagina'  => 'Zonas', 
  'bread'  => [ ['Zonas'] ],
  'assets' => [ 'libs' => ['datatable'], 'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')

  <div class="row">
    <div class="col-md-12"> <a class="btn btn-primary btn-flat pull-right" href="{{ route('zonas.create') }}"> Nueva </a>
    </div>
  </div>

  @include('partials.errors_html')

  @component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Codigo' , 'Nombre' , 'Acciones'] 
  ])
  @slot('body')
    @foreach ($zonas as $zona)
      <tr>
        <td> {{ $zona->ZonCodi }} </td>
        <td> {{ $zona->ZonNomb }} </td>
        <td>
        @php
          $links = [
            ['src' => route('zonas.edit', $zona->ZonCodi),'texto' => 'Modificar'],
            ['src' => '#' , 'texto' => 'Eliminar' , 'class' => 'eliminate-element' , 'id' => $zona->ZonCodi ]
          ];
      @endphp

      @include('partials.column_accion', [ 'links' => $links ])
        </td>
      </tr>
    @endforeach
  @endslot
  @endcomponent

  @include('partials.modal_eliminate', ['url' => route('zonas.destroy' , 'XX') ])
  
@endslot
@endview_data