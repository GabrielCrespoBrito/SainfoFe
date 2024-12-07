@php
$tableInHtml = $tableInHtml ?? false;
@endphp

<div class="row">
  <div class="col-md-12">
    <a 
      style="margin-bottom:10px"
      class="btn btn-flat btn-primary pull-right" 
      target="_blank" 
      href="{{ route('reportes.utilidades.pdf_complete', [
      'fecha_desde' => $fecha_desde,
      'fecha_hasta' => $fecha_hasta,
      'local' => $local,
      'grupo' => $grupo,
      'vendedor' => $vendedor,
      'descontarPorcVendedor' => $descontarPorcVendedor,
       ]) }}"> 
        <span class="fa fa-file-pdf-o"> </span> Ver en PDF </a>
  </div>
</div>
@include('reportes.ganancias.partials.table_dias', [ 'tableInHtml' => $tableInHtml ])

