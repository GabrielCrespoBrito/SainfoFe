@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Cuentas Bancaria',
  'titulo_pagina'  => 'Cuentas Bancaria', 
  'bread'  => [ ['Cuentas Bancaria'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')

  <div class="row">
    <div class="col-md-12"> <a class="btn btn-primary btn-flat pull-right" href="{{ route('cuenta.create') }}"> Nueva </a>
    </div>
  </div>

  @include('partials.errors_html')

  @component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ '#' , 'Banco' , 'Moneda' , 'Nro Cuenta', 'Detraccion', 'Acciones'] 
  ])
  @slot('body')
    @foreach ($cuentas as $cuenta)
      <tr>
        <td> {{ $cuenta->CueCodi }} </td>
        <td> {{ $cuenta->banco->bannomb }} </td>
        <td> {{ $cuenta->moneda->monabre }} </td>
        <td> {{ $cuenta->CueNume }} </td>
        <td> {{ $cuenta->Detract ? 'Si' : 'No' }}  </td>
        <td>
        @php
        $links = [
          ['src' => route('cuenta.edit', $cuenta->CueCodi),'texto' => 'Modificar'],
          ['src' => '#' , 'texto' => 'Eliminar' , 'class' => 'eliminate-element' , 'id' => $cuenta->CueCodi ]
        ];
      @endphp

      @include('partials.column_accion', [ 'links' => $links ])
        </td>
      </tr>
    @endforeach
  @endslot
  @endcomponent

  @include('partials.modal_eliminate', ['url' => route('cuenta.destroy' , 'XX') ])

@endslot  


@endview_data


