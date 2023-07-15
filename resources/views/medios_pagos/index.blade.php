@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Medios de Pagos',
  'titulo_pagina'  => 'Medios de Pagos', 
  'bread'  => [ ['Medios de Pagos'] ],
  'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')

  @component('components.table', ['class_name' => 'sainfo-noicon size-9em', 'thead' => [ 'Nombre' , 'Estado' ,  'Defecto',  ''] ])
    @slot('body')
      @foreach( $medios_pagos as $medio_pago )
        <tr class="estado-medio-pago-{{ $medio_pago->uso }}">
          <td> {{ optional($medio_pago->tipo_pago_parent)->TpgNomb }} </td>
          <td>
            <span class="label-status"> {{ $medio_pago->isUso() ? 'Habilitado' : 'Desactivado' }} </span>
          </td>

          <td>
            @if($medio_pago->isDefault())
            <span class="fa fa-check-square-o"> </span>
            @else

            <a href="{{ route('medios_pagos.set_default', $medio_pago->id ) }}" class="">
              <span class="fa fa-square-o"> </span>
            </a>

            @endif
          </td>


          <td>
            <a title="Modificar" class="btn btn-flat btn-xs btn-{{ $medio_pago->isUso() ? 'default' : 'success' }}" href="{{ route('medios_pagos.toggle_status', $medio_pago->id) }}"> {{ $medio_pago->isUso() ? 'Desactivar' : 'Habilitar' }} </a>
          </td>
        </tr>
      @endforeach
    @endslot
  @endcomponent

@endslot  

@endview_data

