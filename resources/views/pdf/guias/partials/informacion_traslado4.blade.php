@php

$class_name = $class_name ?? "";
$guia2 = $guia2 ?? null;
// Nombre
$section_class = $section_class ?? '';
$section_title_div_class = $section_title_div_class ?? '';
$section_title_value  = $section_title_value ?? '';

$section_data_nombre = $section_data_nombre ?? '';
$section_data_value = $section_data_value ?? '';
// font-size-9 border-width-1 border-color-blue-light border-right-style-solid py-x2 px-x3 text-top
@endphp

<div class="{{ $class_name }}">
<table style="width:100%" class="{{ $table_class }}">
  <tr>

    <td style="width:100%; vertical-align: top;" class="{{ $section_class }}">

      <div class="{{ $section_title_div_class }}">
        <div class="{{ $section_title_value }}"> DATOS DE TRASLADO </div>
      </div> 

      <div>
      <span class="{{ $section_data_nombre }}"> MOTIVO DE TRASLADO: </span>
      <span class="{{ $section_data_value }}"> {{ optional(optional($guia2)->motivoTraslado)->MotNomb  }} </span>
      </div>

      <div>
      <span class="{{ $section_data_nombre }}"> MODALIDAD DE TRASLADO: </span>
      <span class="{{ $section_data_value }}"> {{ optional($guia2)->modadlidadTrasladoNombre()  }} </span>
      </div>

      <div>
      <span class="{{ $section_data_nombre }}"> PESO BRUTO TOTAL : </span>
      <span class="{{ $section_data_value }}"> {{ optional($guia2)->guiporp }} </span>
      </div>      

    </td>

 </tr>

  <tr>
    <td style="width:100%; vertical-align: top;" class="{{ $section_class }}">

      <div class="{{ $section_title_div_class }}">
        <div class="{{ $section_title_value }}">{{ $guia2->isTrasladoPrivado() ? 'TRANSPORTISTA'  : 'EMPRESA DE TRANSPORTE' }}</div>
      </div> 


      {{--  --}}
      @if( $guia2->isTrasladoPrivado() )

      <div>
        <span class="{{ $section_data_nombre }}"> LIC. CONDUCIR: </span>
        <span class="{{ $section_data_value }}"> {{ $guia2->transportista->TraLice }} </span>
        </div>      
      </div>
      
      <div>
        <span class="{{ $section_data_nombre }}"> NOMBRE: </span>
        <span class="{{ $section_data_value }}"> {{ $guia2->transportista->getFullName() }} </span>
        </div>

      <div>
        <span class="{{ $section_data_nombre }}"> DOC.: </span>
        <span class="{{ $section_data_value }}"> {{ $guia2->transportista->getDocumentoNameComplete() }}</span>
        </div>

        <div class="nombre_div_class {{ $section_title_value ?? '' }}">
          <div class="nombre_campo_class {{ $nombre_campo_class ?? '' }}"> VEHICULO </div>
        </div>   

        <div>
        <span class="{{ $section_data_nombre }}"> MARCA: </span>
        <span class="{{ $section_data_value }}"> {{ optional($guia2->vehiculo)->VehMarc }}</span>
        </div>

        <div>
        <span class="{{ $section_data_nombre }}"> PLACA: </span>
        <span class="{{ $section_data_value }}"> {{ optional($guia2->vehiculo)->VehPlac }}</span>
        </div>

    @else

      <div>
      <span class="{{ $section_data_nombre }}"> RAZON SOCIAL: </span>
      <span class="{{ $section_data_value }}"> {{optional($guia2->empresaTransporte)->EmpNomb}}  </span>
      </div>
      
      <div>
      <span class="{{ $section_data_nombre }}"> RUC: </span>
      <span class="{{ $section_data_value }}"> {{ optional($guia2->empresaTransporte)->EmpRucc }} </span>
      </div>

    @endif

    </td>
  </tr>


  <tr>
    <td style="width:100%; vertical-align: top; text-align:center">
      <div class="qr">
        <img style="" src="data:image/png;base64, {!! base64_encode($qr) !!} ">
      </div>
    </td>
  </tr>

</table>
</div>