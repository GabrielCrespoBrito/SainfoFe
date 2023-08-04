@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Produccion',
  'titulo_pagina'  => 'Produccion', 
  'bread'  => [ ['Produccion'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')

  {{-- @include('produccion.partials.filter') --}}
  
  @component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ '#' , 'Producto' , 'Cant.', 'Costo', 'F.Emisión' , 'F.Venc', 'Resp', 'Usuario', 'Estado' , ''] ])

  @slot('body')  

    @foreach( $producciones as $produccion )
      <tr>
        <td> {!! $produccion->presenter()->linkEdit() !!} </td>
        <td> {{ $produccion->manCodi }} - {{ $produccion->manNomb }} </td>
        <td> {{ $produccion->manCant }} </td>
        <td> {{ $produccion->manCostUnit }} </td>
        <td> {{ $produccion->manFechEmis }} </td>
        <td> {{ $produccion->manFechVenc }} </td>
        <td> {{ $produccion->manResp }} </td>
        <td> {{ $produccion->USER_CREA }} </td>
        <td> {!! $produccion->presenter()->getEstado() !!} </td>
        <td> 
          @include('produccion.partials.dropdown', ['id' => $produccion->manId] ) 
        </td>
      </tr>
    @endforeach

  @endslot
  
  @endcomponent

@endslot  

@endview_data