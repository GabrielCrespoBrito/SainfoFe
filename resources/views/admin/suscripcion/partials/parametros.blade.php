@component('components.box', ['className' => 'box-notificacion box-shadow box-border' ])
  @slot('header')
    <span class="fa fa-tasks"> </span> Información
  @endslot

  @slot('body')
    <table class="table table-condensed table-info-suscripcion">
      <tr>
        <td class="nombre"> <span class="fa fa-calendar"> </span> Duración </td>
        <td class="valor cant-duracion"> {{ $suscripcion->getDuracionNombre() }}  </td>
      </tr>

      <tr>
        <td class="nombre"><span class="fa  fa-calendar-check-o"> </span> Fecha inicio</td>
        <td class="valor"> {{ $suscripcion->fecha_inicio }} </td>
      </tr>

      <tr>
        <td class="nombre"><span class="fa fa-calendar-times-o"> </span> Fecha final</td>        
        <td class="valor">
          {{-- <input data-initial="{{ $suscripcion->fecha_final }}" class="input-date-vencimiento" type="date" disabled value="{{ $suscripcion->fecha_final }}"> --}}
          <input data-initial="{{ $suscripcion->fecha_final }}" class="input-date-vencimiento disabled-no-border" type="text" disabled value="{{ $suscripcion->fecha_final }}">

          <a href="#" class="active-update-input-fecha-suscripcion"> <span class="fa fa-pencil"> </span> </a>
          <a href="#" style="display:none" class="update-fecha-suscripcion" data-url="{{ route('admin.suscripcion.update_fecha', $suscripcion->id ) }}"> <span class="fa fa-save"> </span> </a> 
          <a href="#" style="display:none" class="desactive-update-input-fecha-suscripcion"> <span class="fa fa-trash"> </span> </a>



        </td>

      </tr> 


      <tr>
        <td class="nombre"> <span class="fa fa-hourglass-start"> </span> Dias restantes</td>
        <td class="valor"> <span class="cant-dias"> {{ $suscripcion->diasRestanteNumber() }}</span> días </td>
      </tr>
    </table>
  @endslot

@endcomponent