@php

$isAnulado = $venta->VtaEsta == "A";
$anulacionPdfRoute = "#";
$anulacionCdrRoute = "#";

$formato_letra = 'a4';
if( $formato == '0'){
$formato_letra = 'a4';
}

if( $formato == '1'){
$formato_letra = 'a4';
}

if( $formato == '2'){
$formato_letra = 'ticket';
}
@endphp

<div class="seccion seccion-recursos">

  <div class="title-section"> <span> Descargas </span>
    <a class="btn btn-xs btn-primary pull-right btn-flat" title="Descargar todos los archivos" href="{{ route('ventas.descargar_recursos' , $venta->VtaOper ) }}"> <span class="fa fa-download"></span> </a>
  </div>

  <div class="col-md-12 no-p">
    @if( $venta->VtaXML )
    <a href="{{ route('ventas.ver_xml' , $venta->VtaOper ) }}" target="_blank" class="btn text-left btn-xs btn-block btn-primary btn-flat">
      <span class="fa  fa-file-text-o"> </span> XML </a>
    @else
    <a href="#" class="text-left btn disabled btn-block btn-xs btn-default btn-flat">
      <span class="fa fa-file-text-o"> </span> XML - No disponible </a>
    @endif
  </div>

  <div class="col-md-8 no-p">
    @if( $venta->VtaPDF )
    <a data-href_default="{{ route('ventas.imprimirFactura' , [ 'id_factura' => $venta->VtaOper, 'formato' => '@@', 'download' => '0'] ) }}" href="{{ route('ventas.imprimirFactura' , [ 'id_factura' => $venta->VtaOper, 'formato' => $formato_letra,  'download' => '0' ]) }}" target="_blank" class="btn-flat pdf-enlace btn btn-block btn-xs btn-primary btn-flat">
      <span class="fa fa-file-pdf-o"> </span> PDF </a>
    @else
    <a href="#" class="btn text-left disabled btn-block btn-xs btn-default btn-flat">
      <span class="fa fa-file-pdf-o"> </span> PDF - No disponible </a>
    @endif
  </div>

  <div class="col-md-2 no_pl no_pr">
    <select class="formato_pdf" style="width:100%; height: 23px;" name="formato_pdf" style="width:100%;height:1.6em">
      <option {{ $formato == '0' ? 'selected=selected' : '' }} value="a4">A4</option>
      <option {{ $formato == '1' ? 'selected=selected' : '' }} value="a5">A5</option>
      <option {{ $formato == '2' ? 'selected=selected' : '' }} value="ticket">Ticket</option>
    </select>
  </div>

  <div class="col-md-2 no-p">

    <a data-href_default="{{ route('ventas.imprimirFactura', ['id_factura' => $venta->VtaOper, 'formato' => '@@', 'download' => '1']) }}" data-toggle="tooltip" title="Descarga Directa" href="{{ route('ventas.imprimirFactura' , [ 'id_factura' => $venta->VtaOper, 'formato' => $formato_letra, 'download' => '1'  ]) }}" class="pdf-enlace btn btn-xs btn-success btn-block btn-flat">
      <span class="fa fa-download"></span> </a>
      
  </div>

  <div class="col-md-12 no-p">
    @if( $venta->VtaCDR )
    <a href="{{ route('ventas.ver_cdr' , $venta->VtaOper ) }}" target="_blank" class="btn btn-xs btn-block btn-primary btn-flat">
      <span class="fa fa-file-zip-o"> </span> CDR </a>
    @else
    <a href="#" class="btn disabled btn-block btn-xs btn-default btn-flat">
      <span class="fa fa-file-zip-o"> </span> CDR - No disponible </a>
    @endif
  </div>

  <br>

  {{-- Rutas para anulaciòn --}}
  @if( $isAnulado )
  <div class="col-md-12 no-p" style="margin-top:5px">
    <a href="{{ route('ventas.recurso_anulacion' , [  'id' => $venta->VtaOper , 'tipo' => 'pdf' ] ) }}" target="_blank" class="btn text-left btn-xs btn-block btn-danger btn-flat">
      <span class="fa fa-file-pdf-o"> </span> PDF Anulaciòn </a>
  </div>

  <div class="col-md-12 no-p">
    <a href="{{ route('ventas.recurso_anulacion' , [  'id' => $venta->VtaOper , 'tipo' => 'cdr' ] ) }}" target="_blank" class="btn text-left btn-xs btn-block btn-danger btn-flat">
      <span class="fa fa-file-zip-o"> </span> CDR Anulaciòn </a>
  </div>
  @endif
  {{-- Rutas para anulaciòn --}}

</div>
</div>