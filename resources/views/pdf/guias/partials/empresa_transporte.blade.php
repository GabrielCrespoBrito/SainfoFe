@php
$border_color = $border_color ?? 'border-color-black';
@endphp

{{-- TD EMPRESA TRANSPORTE --}}
<td width="30%" class="p-x3 border-style-solid border-radius-5 border-width-2 {{ $border_color }}" valign="top">
  <div class="seccion unidad">
    <p class="title_pie bold border-bottom-style-solid border-width-2 {{ $border_color }}"> EMPRESA DE TRANSPORTE </p>

    <p class="data font-size-8">
      <span class="attr"> NOMBRE: </span>
      <span class="value"> {{ optional($guia2->empresaTransporte)->EmpNomb }} </span>
    </p>

    <p class="data font-size-8">
      <span class="attr"> RUC.: </span>
      <span class="value"> {{ optional($guia2->empresaTransporte)->EmpRucc }}</span>
    </p>

    <div class="qr">
      <img src="data:image/png;base64, {!! base64_encode($qr)!!} ">
    </div>

  </div>
</td>
{{-- TD EMPRESA TRANSPORTE --}}