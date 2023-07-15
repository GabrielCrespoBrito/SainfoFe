<div class="nombre_div_class {{ $nombre_div_class }}">
<div class="nombre_campo_class {{ $nombre_campo_class }}"> VEHICULO </div>
</div>   

<div>
<span class="bold"> MARCA: </span>
<span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2->vehiculo)->VehMarc }}</span>
</div>

<div>
<span class="bold"> PLACA: </span>
<span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ optional($guia2->vehiculo)->VehPlac }}</span>
</div>