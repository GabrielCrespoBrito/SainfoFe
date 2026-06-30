@php
    $class_name = $class_name ?? '';
    $titulo_div_class = $titulo_div_class ?? '';
    $cuenta_text_class = $cuenta_text_class ?? '';
    $cuenta_banco_text_class = $cuenta_banco_text_class ?? '';
    $cuenta_cuenta_text_class = $cuenta_cuenta_text_class ?? '';
    $titulo = $titulo ?? 'CUENTAS';
@endphp

<div class="cuentas {{ $class_name }}">

    @if ($titulo)
        <div class="titulo_div_class {{ $titulo_div_class }}"> {{ $titulo }} </div>
    @endif

    @foreach ($bancos as $cuenta)
        <div class="cuenta_text_class {{ $cuenta_text_class }}">
            <span class="{{ $cuenta_banco_text_class }}">
                @if (isset($cuenta['banco_image']))
                    @if ($cuenta['banco_image'])
                        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($cuenta['banco_image'])) }}"
                            alt="{{ $cuenta['banco_nombre'] }}" style="width: 25px; margin-right: 10px;">
                    @endif
                @endif
                {{ $cuenta['banco_nombre'] }} {{ $cuenta['banco_moneda'] }}
            </span>
            <span class="{{ $cuenta_cuenta_text_class }}"> {{ $cuenta['banco_cuenta'] }} </span>
        </div>
    @endforeach

</div>
