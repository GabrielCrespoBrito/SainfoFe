@php
$class_name = $class_name ?? "";
$titulo_div_class = $titulo_div_class ?? '';
$titulo = $titulo ?? 'CONDICIONES';
$condicion_div_class = $condicion_div_class ?? 'cuenta_text_class pl-x3 font-size-8';
@endphp

<div class="cuentas {{ $class_name }}">
  
  @if( $titulo )
  <div class="titulo_div_class {{ $titulo_div_class }}"> {{ $titulo }} </div>
  @endif

  @foreach( $condiciones as $condicion )
  <div class="{{ $condicion_div_class }}">
    <div> {{ $condicion }} </div>
  </div>
  @endforeach

</div>