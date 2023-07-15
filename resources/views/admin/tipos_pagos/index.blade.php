@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Tipos de Pago',
'titulo_pagina' => 'Tipos de Pago',
'bread' => [['Tipos de Pago']],
])

@slot('contenido')

  <div>
    <a href="{{ route('admin.tipo_pago.create') }}" class="pull-right btn btn-flat btn-primary"> <span class="fa fa-plus"></span> Nuevo  </a>
  </div>

  @component('components.table', ['class_name' => 'sainfo-noicon size-9em', 'thead' => ['Codigo','Nombre', 'Bancario',  ''] ])
    @slot('body')
      @foreach( $tipos_pagos as $tipo_pago )
        <tr>
          <td> {{ $tipo_pago->TpgCodi }} </td>
          <td> {{ $tipo_pago->TpgNomb }} </td>
          <td> 
            {{-- {{ $tipo_pago->TdoBanc }} </td> --}}
            @if( $tipo_pago->TdoBanc )
              <label class="label label-success"> Si </label>
            @else
              <label class="label label-default"> No </label>
            @endif
          <td>
            <a class="btn btn-flat btn-xs btn-default" href="{{ route('admin.tipo_pago.edit', $tipo_pago->TpgCodi) }}">   <span class="fa fa-pencil"></span> Modificar </a>
          </td>          
        </tr>
      @endforeach
    @endslot
  @endcomponent


@endslot

@endview_data