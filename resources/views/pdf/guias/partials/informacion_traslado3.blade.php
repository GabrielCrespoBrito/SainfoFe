@php

$class_name = $class_name ?? "";
$guia2 = $guia2 ?? null;
// Nombre
$nombre = $nombre ?? "-";
$nombre_div_class = $nombre_div_class ?? '';
$nombre_campo_nombre = $nombre_campo_nombre ?? '';
$nombre_campo_class = $nombre_campo_class ?? '';
$descripcion_campo_class = $descripcion_campo_class ?? '';
$nombre_text_class = $nombre_text_class ?? '';

@endphp


<div class="{{ $class_name }}">
<table style="width:100%" class="{{ $table_class }}">
  <tr>
  
    <td style="width:35%; vertical-align: top;" class="font-size-9 border-width-1 border-color-blue-light border-right-style-solid py-x2 px-x3 text-top">

      
      @if($isGuiaTransportista)
        @include('pdf.guias.partials.empresa_transporte_data')

      @else

      <div class="pt-x10 nombre_div_class {{ $nombre_div_class }}">
        <div class="nombre_campo_class {{ $nombre_campo_class }}"> DATOS DE TRASLADO </div>
      <div>

      <div>
      <span class="bold"> MOTIVO DE TRASLADO: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional(optional($guia2)->motivoTraslado)->MotNomb  }} </span>
      </div>

      <div>
      <span class="bold"> MODALIDAD DE TRASLADO: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->modadlidadTrasladoNombre()  }} </span>
      </div>

      <div>
      <span class="bold"> PESO BRUTO TOTAL : </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guiporp }} </span>
      </div>      
      
      @endif


    </td>

    <td style="width:45%; vertical-align: top;" class="font-size-9 py-x2 px-x3 text-top border-width-1 border-right-style-solid border-color-blue-light">

      @if( !$guia2->isTrasladoPrivado() && $isGuiaTransportista == false )
        @include('pdf.guias.partials.empresa_transporte_data')
      @endif

      @if( $guia2->isTrasladoPrivado() || $isGuiaTransportista )
        @include('pdf.guias.partials.transportista_data')
        @include('pdf.guias.partials.vehiculo_data')
      @endif  

    </td>

    <td style="width:20%;overflow:hidden;" class="position-relative py-x2 px-x3 text-top border-right-style-solid">    
      <div class="qr" style="">
        <img style="" src="data:image/png;base64, {!! base64_encode($qr) !!} ">
      </div>
    </td>   

 </tr>
</table>
</div>