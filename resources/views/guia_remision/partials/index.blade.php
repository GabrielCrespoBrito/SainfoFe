@php
  $isSalida = $isSalida ?? false;
  $nombre = $isSalida ? ' Remisión ' : ' Ingreso';
  $columns =  ['N° Oper', 'N° Doc' , 'Refer' , 'Fecha' , $entidad , 'Almacen', 'Estado' , '' ];
  $status = $status ?? null;
  $motivo_traslado = $motivo_traslado ?? null;
@endphp

@view_data([
  'layout' => 'layouts.master' , 
  'title'  => $titulo,
  'titulo_pagina'  => $titulo, 
  'bread'  => [ [ $titulo ] ],
  'assets' => ['libs' => ['datatable', 'popover'],'js' => ['helpers.js','guia/mix/index_mix.js']]

])

@slot('js')
  <script>
    window.url_sendcorre_format = "{{ route('guia.sent_email', '@@') }}"
  </script>
  @include('partials.errores')
@endslot

@slot('contenido')
  <input type="hidden" name="status" value="{{ $status }}">
  @include('guia_remision.partials.filters', [ 'motivo_traslado' => $motivo_traslado, 'isSalida' => $isSalida, 'routeCreate' => $routeCreate, 'format' => $format ])

  @component('components.table', [ 'id' => 'datatable' , 'url' => $routeSearch , 'class_name' => 'sainfo-noicon size-9em', 'thead' => $columns ])  
  @endcomponent
  
  @include('partials.modal_eliminate', ['url' => route('compras.destroy' , 'XX') ])
  
  @include('cotizaciones.partials.modal_redactar_correo', ['asunto' => 'Guia de remisión' ])

@endslot  

@endview_data