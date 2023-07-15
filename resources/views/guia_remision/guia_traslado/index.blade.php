@view_data([
'layout' => 'layouts.master' ,
'title' => 'Traslados',
'titulo_pagina' => 'Traslados',
'bread' => [ ['Traslados ' ] ],
'assets' => ['libs' => ['datatable', 'popover'],'js' => ['helpers.js','guia/traslado.js' ]]
])

@slot('js')
<script>
  window.url_sendcorre_format = "{{ route('guia.sent_email', '@@') }}"
</script>
@include('partials.errores')
@endslot

@slot('contenido')

{{-- Filtros --}}
<div class="row">
  @include('guia_remision.guia_traslado.partials.filters')
</div>
{{-- /Filtros --}}

@php
$thead = [ 'N° Oper' , 'N° Doc' , 'Fecha' ];

if($salida){
  $thead[] = 'Estado Traslado';
  $thead[] = 'Guia Traslado';
}

else {
  $thead[] = 'Estado Recepcion';
  $thead[] = 'Observacion';
  $thead[] = 'Guia';
}
@endphp


@component('components.table', [
'id' => 'datatable',
'url' => route('guia_traslado.search'),
'class_name' => 'sainfo-noicon size-9em',
'thead' => $thead
])
@endcomponent

@include('guia_remision.guia_traslado.partials.modal_conformidad')

@php
$tipo_movimiento = new App\TipoMovimiento();
$tipo_movimientos = $tipo_movimiento->repository()->where('TmoInSa', 'I');
@endphp
@include('ventas.partials.modal_guiasalida', [
'local_disabled' => '@@@@',
'tipoVenta' => true,
'titulo' => 'Realizar Traslado',
'showCancelBtn' => false,
'guia_id' => '@@@@',
'showFecha' => true,
'showForm' => true,
'id' => 'modalTraslado',
'tipo_movimientos' => $tipo_movimientos,
])


@endslot

@endview_data