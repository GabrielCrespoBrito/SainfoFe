<div class="pt-x10 nombre_div_class {{ $nombre_div_class }}">
<div class="nombre_campo_class {{ $nombre_campo_class }}"> 
  EMPRESA DE TRANSPORTE
</div>
</div>

<div>
<span class="bold"> RAZON SOCIAL: </span>
<span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{optional($guia2->empresaTransporte)->EmpNomb}}  </span>
</div>

<div>
<span class="bold"> RUC: </span>
<span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2->empresaTransporte)->EmpRucc }} </span>
</div>