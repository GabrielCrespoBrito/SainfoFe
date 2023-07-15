@component('components.box', ['className' => 'box-notificacion box-shadow box-border' ])
  @slot('header')
    <span class="fa fa-info"> </span> Información
  @endslot

  {{-- @dd( $suscripcion->usos->first()->  ) --}}

  @slot('body')

    <table class="table table-condensed table-info-suscripcion">
      <thead>
        <tr>
          <td>Campo  </td>
          <td> Uso </td>
          <td> Maximo </td>
          <td> Quedan </td>
        </tr>
      </thead>

      <tbody>
        @foreach( $suscripcion->usos as $uso )       
          @if( $uso->caracteristica->isTipoConsumo() )
          <tr>
            <td class="nombre"> {{ $uso->caracteristica->nombre }} </td>
            <td class="valor">{{ $uso->uso }} </td>
            <td class="valor">{{ $uso->limite }} </td>
            <td class="valor">{{ $uso->restante }} </td>
          </tr>
          @endif

        @endforeach

      </tbody>
    </table>
  @endslot
      
  @endcomponent