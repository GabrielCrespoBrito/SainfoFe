@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Produccion',
  'titulo_pagina'  => 'Produccion', 
  'bread'  => [ ['Produccion'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','produccion/index.js']]
])

@slot('contenido')

  @include('produccion.partials.filter')
  @include('produccion.partials.form_eliminar')

  @component('components.table', [ 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ '#' , 'Producto' , 'Cant.', 'Costo', 'F.Emisión' , 'F.Venc', 'Resp', 'Usuario', 'Estado' , ''] ])

  @slot('body')  

    @foreach( $producciones as $produccion )
      <tr>
        <td> {!! $produccion->presenter()->linkShow() !!} </td>
        <td> {{ $produccion->manCodi }} - {{ $produccion->manNomb }} </td>
        <td> {{ $produccion->manCant }} </td>
        <td> {{ $produccion->manCostUnit }} </td>
        <td> {{ $produccion->manFechEmis }} </td>
        <td> {{ $produccion->manFechVenc }} </td>
        <td> {{ $produccion->manResp }} </td>
        <td> {{ $produccion->USER_CREA }} </td>
        <td class="edit-estado-column"> 

          @if($produccion->canChangeEstado())
          <div class="div-estado-edit" style="display:none">
          <form action="{{ route('produccion.cambiarEstado', ['id' => $produccion->manId]) }}" method="post">
          @csrf
          <select name="estado" class="">
            @foreach( $produccion->getEstadosToEdit() as $id => $estadoNombre )
            <option value="{{ $id }}">{{ $estadoNombre }} </option>
            @endforeach
          </select>
          <button type="submit" class="btn-default btn-flat btn-xs"> <span class="fa fa-save"></span> </button>
          <a href="#" class="btn-edit-close btn-default btn-flat btn-xs"> <span class="fa fa-close"></span> </a>
          </form>
          </div>
          @endif

          <div class="div-estado-actual">
          {!! $produccion->presenter()->getEstado() !!} 
          @if($produccion->canChangeEstado())
           <span class="btn-edit-estado fa fa-pencil"></span>
          @endif
          </div>
        
        </td>

        <td> 
          @include('produccion.partials.dropdown', ['id' => $produccion->manId] ) 
        </td>
      </tr>
    @endforeach

  @endslot
  
  @endcomponent

@endslot  

@endview_data