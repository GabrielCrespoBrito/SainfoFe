@component('components.box', ['className' => 'box-notificacion box-shadow box-border' ])
  @slot('header')
    <span class="fa fa-tasks"> </span> Información
  @endslot

  @slot('body')
    <table class="table table-condensed table-info-suscripcion">
      <tr>
        <td class="nombre"> <span class="fa fa-calendar"> </span> Duración </td>
        <td class="valor"> {{ $suscripcion->getDuracionNombre() }}  </td>
      </tr>

      <tr>
        <td class="nombre"><span class="fa  fa-calendar-check-o"> </span> Fecha inicio</td>
        <td class="valor"> {{ $suscripcion->fecha_inicio  }} </td>
      </tr>

      <tr>
        <td class="nombre"><span class="fa fa-calendar-times-o"> </span> Fecha final</td>
        <td class="valor"> {{ $suscripcion->fecha_final  }} </td>
      </tr>

      <tr>
        <td class="nombre"> <span class="fa fa-hourglass-start"> </span> Dias restantes</td>
        <td class="valor"> {{ $suscripcion->diasRestanteNumber() }} días </td>
      </tr>
    </table>
  @endslot

@endcomponent