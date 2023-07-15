@php
$class_name = $class_name ?? "";
$class_qr_div = $class_qr_div ?? "";
$info_adicional_class = $info_adicional_class ?? '';
$info_text_div_class = $info_text_div_class ?? '';
$info_nombre_class = $info_nombre_class ?? '';
$info_text_class = $info_text_class ?? '';
$info_text_consultada_class = $info_text_consultada_class ?? ''; 
$info_nombre_consultada_class = $info_nombre_consultada_class ?? '';

@endphp

<div class="info_adicional {{ $class_name }}">

{{-- QR Secciòn --}}
<div class="qr_div {{ $class_qr_div }}">
  <img 
  style="
    padding: 0;
    margin: 0;"
  src="data:image/png;base64, {!! base64_encode($qr)!!} ">
</div>


{{-- Informaciòn --}}
<div class="info_adicional {{ $info_adicional_class }}">

<div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_nombre {{ $info_nombre_class }}">Resumen</span>: 
  <span class="info_text {{ $info_text_class  }}"> {{ $hash }}= </span>
</div>

<div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_nombre {{ $info_nombre_class }}">Hora</span>:
  <span class="info_text {{ $info_text_class  }}"> {{ $hora }} </span>
</div>

<div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_nombre {{ $info_nombre_class }}">Peso</span>:
  <span class="info_text {{ $info_text_class  }}"> {{ $peso }} Kgs. </span>
</div>

<div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_nombre {{ $info_nombre_class }}">Representaciòn Impresa de:</span>
</div>

<div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_text {{ $info_text_class }}"> {{ $nombreDocumento }}<span>
</div>

<div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_text {{ $info_text_class }} {{ $info_text_consultada_class  }}">Esta puede ser consultada en:<span>
</div>

<div class="info_text_div {{ $info_text_div_class }}">
  <span class="info_nombre {{ $info_nombre_class }} {{ $info_nombre_consultada_class }}">  {{ $pageDocumento }} <span>
</div>

</div>

{{-- Informaciòn --}}



</div>