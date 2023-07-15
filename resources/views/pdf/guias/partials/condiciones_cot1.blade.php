@php
$class_name = $class_name ?? "";
$titulo_div_class = $titulo_div_class ?? '';
$titulo = $titulo ?? 'CONDICIONES';
@endphp

<div class="cuentas {{ $class_name }}">
  
  @if( $titulo )
  <div class="titulo_div_class {{ $titulo_div_class }}"> {{ $titulo }} </div>
  @endif

  @foreach( $condiciones as $condicion )
  <div class="cuenta_text_class">
    <div> {{ $condicion }} </div>
  </div>
  @endforeach

</div>