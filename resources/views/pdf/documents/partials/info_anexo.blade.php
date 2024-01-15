@php
$class_name = $class_name ?? "";
$class_name_bloque = $class_name_bloque ?? '';
$titulo_info_class = $titulo_info_class ?? 'pl-x3';
@endphp

{{-- Placa --}}
@if($venta2->hasPlaca())
<div class="{{ $class_name }}">
  <span class="{{ $titulo_info_class }}">Placa:</span>
  <span class="bold pl-x10 pr-x10"> {{ $venta2->getPlaca() }} </span>
</div>
@endif


{{-- Documento de Referencia --}}
@if( $venta2->isNota() )
<div class="{{ $class_name }}">
  <span class="{{ $titulo_info_class }}">Referencia:</span>
  <span class="bold pl-x10 pr-x10">{{ $venta2->VtaTDR }}</span>
  <span class="bold pr-x10">{{ $venta2->VtaSeriR }}</span>
  <span class="bold pr-x10">{{ $venta2->VtaNumeR }}</span>
  <span class="bold pr-x10">{{ $venta2->VtaFVtaR }}</span>
  <span class="ml-x10"><span class="bold">Motivo:</span>
  <span class="ml-x5">{{ $venta2->VtaObse }}</span>
</div>
@endif


{{-- Observación --}}
@if( $venta2->isNota() == false && $venta2->VtaObse  )
<div class="{{ $class_name }}">
  <span class="{{ $titulo_info_class }}">Observación:</span>
  <span class="bold pl-x10 pr-x10"> {{ $venta2->VtaObse }} </span>
</div>
@endif

{{-- Detracciòn --}}
@if($venta2->hasDetraccion())
<div class="{{ $class_name }}">
  <span class="{{ $titulo_info_class }}">Detracciòn:</span>
  <span class="bold pl-x10 pr-x10">{{ $venta2->detraccion->descripcion }} ({{ $venta2->VtaDetrCode }})</span>
  <span class="pr-x10"></span>
  <span class="bold pr-x10">Porcentaje</span>
  <span class="pr-x10">{{ fixedValue($venta2->VtaDetrPorc) }} %</span>
  <span class="bold pr-x10">Total:</span>
  <span class="pr-x10">{{ fixedValue($venta2->VtaDetrTota) }}</span>
</div>
@endif

{{-- Retenciòn --}}
@if( $venta2->hasMontoRetencion() )
<div class="{{ $class_name }}">
  <span class="{{ $titulo_info_class }}">Información de la retención:</span>
  <span class="bold pl-x10 pr-x10">Base imponible</span>
  <span class="pr-x10"> {{ $moneda_abreviatura }} </span>
  <span class="pr-x10">{{ fixedValue($venta2->VtaImpo) }}</span>
  <span class="bold pr-x10">Porcentaje</span>
  <span class=" pr-x10">{{ $venta2->retencionPorc() }}%</span>
  <span class="bold pr-x10">Monto:</span>
  <span class="pr-x10">{{ $moneda_abreviatura }}</span>
  <span class="pr-x10">{{ $venta2->retencionMonto() }}</span>
</div>
@endif

{{-- Anticipo --}}
@if($venta2->hasAnticipo())
<div class="{{ $class_name }}">
  <span class="{{ $titulo_info_class }}">Anticipo:</span>
  <span class="bold pl-x3">Doc:</span>
  <span class="pl-x10 pr-x10"> {{ $venta2->getNombreTipoDocucumentoAnticipo() }} {{ $venta2->VtaNumeAnticipo }} <span>
  <span class="bold pl-x10 pr-x10"> Total: </span>
  <span class="pl-x10 pr-x10"> {{ $moneda_abreviatura }} {{ decimal($venta2->VtaTotalAnticipo) }} </span>
</div>
@endif

{{-- Monto en palabras --}}
<div class="{{ $class_name }}">
  <span class="{{ $titulo_info_class }}">Son:</span>
  <span class="bold pl-x10 pr-x10"> {{ $cifra_letra }}</span>
</div>