@php
$border_color = $border_color ?? 'border-color-black';
@endphp

{{-- TD MOTIVO TRASLADO --}}
<td width="{{ $guia2->isIngreso() ? "100%" : "40%"  }}" class=" p-x3 border-style-solid border-radius-5 border-width-2 {{ $border_color }}" valign="top">

  <div class="seccion unidad ">
    <p class="title_pie bold border-bottom-style-solid border-width-2 {{ $border_color}}"> MOTIVO DE TRASLADO </p>

    <table class="table_motivo font-size-8" width="100%">
      @foreach( $motivos_traslado->chunk(2) as $motivos )
      <tr>
        @foreach( $motivos as $motivo )
        <td width="50%" class="mb-x5 pb-x10">
          <span class="name_motivo  {{ $guia['motcodi'] == $motivo->MotCodi ? 'checked' : '' }} "> {{ $motivo->MotNomb }} </span>
        </td>
        @endforeach
      </tr>
      @endforeach
    </table>
  </div>
</td>
{{-- TD MOTIVO TRASLADO --}}