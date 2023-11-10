@php
$class_name = $class_name ?? "";
$totales_seccion = $totales_seccion ?? "";
$border = $border ?? true;
$titulo_div_class = $titulo_div_class ?? '';
$cuenta_text_class = $cuenta_text_class ?? '';
$cuenta_banco_text_class = $cuenta_banco_text_class ?? '';
$cuenta_cuenta_text_class = $cuenta_cuenta_text_class ?? '';
$titulo = $titulo ?? 'CUENTAS';

@endphp

<div class="cuentas {{ $class_name }}">
  
  <table class="width-100 font-size-9 {{ $border ? 'border-bottom-style-solid border-color-blue-light border-width-1' : '' }} ">

    @if( $mostrar_igv )

    <tr>
      <td class="width-50 bold pl-x5 {{ $border ? 'border-bottom-style-solid border-right-style-solid border-color-blue-light border-width-1' : '' }} ">VALOR VENTA</td>
      <td class="width-50 text-right pl-x10 pr-x5 {{ $border ? 'border-bottom-style-solid border-color-blue-light border-width-1' : '' }} ">{{ decimal( $base, 2) }}  
      <span class="bold pull-left"> {{ $moneda_abreviatura }}</span> </td>
    </tr>

    <tr>
      <td class="width-50  bold pl-x5 {{ $border ? 'border-bottom-style-solid border-right-style-solid border-color-blue-light border-width-1' : '' }}">I.G.V {{ $igv_porcentaje }}%</td>
      <td class="width-50 text-right pl-x10 pr-x5 {{ $border ? 'border-bottom-style-solid border-color-blue-light border-width-1' : '' }}" >{{ decimal( $igv ,2) }}</td>
    </tr>

    @endif

    <tr>
      <td class="width-50  bold pl-x5 {{ $border ? 'border-bottom-style-solid border-right-style-solid border-color-blue-light border-width-1' : '' }}">TOTAL VENTA </td>
      <td class="width-50 text-right pl-x10 pr-x5 {{ $border ?  'border-bottom-style-solid border-color-blue-light border-width-1' : '' }} "> {{ decimal( $total, 2) }}
      <span class="bold pull-left"> {{ $moneda_abreviatura }}</span>
      </td>
    </tr>        

  </table>

  <div class="titulo_div_class {{ $titulo_div_class }}"> {{ $titulo }} </div>

  @foreach( $bancos as $cuenta )
  <div class="cuenta_text_class {{ $cuenta_text_class }}">
    <span class="{{ $cuenta_banco_text_class }}"> {{ $cuenta['banco_nombre'] }} {{ $cuenta['banco_moneda'] }} </span>
    <span class="{{ $cuenta_cuenta_text_class }}"> {{ $cuenta['banco_cuenta'] }} </span>
  </div>
  @endforeach

</div>