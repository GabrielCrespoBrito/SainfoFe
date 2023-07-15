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
<table style="width:100%">
  <tr>

    <td style="width:30%" class="font-size-9 border-width-1 border-color-blue-light border-right-style-solid py-x2 px-x3 text-top">
      <div class="nombre_div_class {{ $nombre_div_class }}">
        <div class="nombre_campo_class {{ $nombre_campo_class }}"> DATOS DE TRASLADO </div>
      <div>

      <div>
      <span class="bold"> MOTIVO DE TRASLADO: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional(optional($guia2)->motivoTraslado)->MotNomb  }} </span>
      </div>
      <div>
      <span class="bold"> PESO BRUTO TOTAL : </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2)->guiporp }} </span>
      </div>      

    </td>

    <td style="width:35%" class="font-size-9 py-x2 px-x3 text-top border-width-1 border-right-style-solid border-color-blue-light">
    
      <div class="nombre_div_class {{ $nombre_div_class }}">
        <div class="nombre_campo_class {{ $nombre_campo_class }}"> UNIDAD TRANSPORTE </div>
      </div>       
      
      <div>
      <span class="bold"> VEH. MARCA: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2->vehiculo)->VehMarc }} </span>
      </div>

      <div>
      <span class="bold"> VEH. PLACA: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2->vehiculo)->VehPlac  }} </span>
      </div>

      <div>
      <span class="bold"> CERT. DE INSCRIP: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2->vehiculo)->VehInsc  }} </span>
      </div>

     <div>
      <span class="bold"> LIC. CONDUCIR: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ $guia2->transportista->TraLice  }} </span>
      </div>      
     <div>
     
      <span class="bold"> TRANSP. NOMBRE: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ $guia2->transportista->getFullName()  }} </span>
      </div>

      <span class="bold"> TRANSP. RUC/DNI: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ $guia2->transportista->TraRucc }} </span>
      </div>

    </td>

    <td style="width:35%;overflow:hidden;" class="position-relative  font-size-9 py-x2 px-x3 text-top border-right-style-solid">
    
      <div class="nombre_div_class {{ $nombre_div_class }}">
        <div class="nombre_campo_class {{ $nombre_campo_class }}"> EMPRESA DE TRANSPORTE </div>
      </div>       
      
      <div>
      <span class="bold"> NOMBRE: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{optional($guia2->empresaTransporte)->EmpNomb}}  </span>
      </div>
      
      <div>
      <span class="bold"> RUC: </span>
      <span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2->empresaTransporte)->EmpRucc }} </span>
      </div>

      <div class="qr" style="position: absolute; top:20px; right:10px">
        <img style="width: 90px; height: 90px" src="data:image/png;base64, {!! base64_encode($qr) !!} ">
      </div>

    </td>    
    
  </tr>
</table>
</div>