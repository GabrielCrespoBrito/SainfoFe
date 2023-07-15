@php
$border_color = $border_color ?? 'border-color-black';
@endphp

{{-- TD UNIDAD TRANSPORTE --}}
<td width="30" class="td_principal p-x3 border-style-solid border-radius-5 border-width-2 {{ $border_color }}" valign="top">

  <div class="seccion unidad">
    <p class="title_pie bold border-bottom-style-solid border-width-2 {{ $border_color }}"> UNIDAD DE TRANSPORTE </p>

    <table width="100%">

      <tr class="data">
        <td width="50%" class="pt-x3 text-uppercase font-size-8 bold"> <span class=" attr"> Vehiculo Marca:</span> </td>
        <td class="pb-x3" width="50%"> {{ optional($guia2->vehiculo)->VehMarc }} </td>
      </tr>

      <tr class="data">
        <td class="pt-x3 text-uppercase font-size-8 bold"> <span class="attr bold"> Vehiculo Placa:</span> </td>
        <td class="pb-x3"> {{ optional($guia2->vehiculo)->VehPlac }} </td>
      </tr>

      <tr class="data">
        <td class="pt-x3 text-uppercase font-size-8"> <span class="attr bold"> Certificado de Inscripción :</span> </td>
        <td class="pb-x3"> {{ optional($guia2->vehiculo)->VehInsc }} </td>
      </tr>

      <tr class="data">
        <td class="pt-x3 text-uppercase font-size-8"> <span class="attr bold"> Licencia conducir:</span> </td>
        <td class="pb-x3"> {{ $guia2->transportista->TraLice }} </td>
      </tr>

      <tr class="data">
        <td class="pt-x3 text-uppercase font-size-8"> <span class="attr bold"> Transportista :</span> </td>
        <td class="pb-x3"> {{ $guia2->transportista->getFullName() }} </td>
      </tr>

      <tr class="data">
        <td class="pt-x3 text-uppercase font-size-8"> <span class="attr bold"> RUC/DNI:</span> </td>
        <td class="pb-x3"> {{ $guia2->transportista->TraRucc }} </td>
      </tr>
    </table>
    <br><br><br>
    <div class="seccion unidad empresa_ text-c">
      <p class="{{ $border_color }}" style="text-align:center; border-top-width:1px; border-top-style:solid;">RECIBI CONFORME</p>
    </div>
  </div>

</td>
{{-- TD UNIDAD TRANSPORTE --}}