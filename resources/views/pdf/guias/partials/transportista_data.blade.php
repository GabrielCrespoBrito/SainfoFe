<div class="pt-x10 nombre_div_class {{ $nombre_div_class }}">
<div class="nombre_campo_class {{ $nombre_campo_class }}"> TRANSPORTISTA</div>
</div>

<div>
<span class="bold"> LIC. CONDUCIR: </span>
<span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ $guia2->transportista->TraLice }} </span>
</div>      
</div>

<div>
<span class="bold"> NOMBRE: </span>
<span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ $guia2->transportista->getFullName() }} </span>
</div>

<div>
<span class="bold"> DOC.: </span>
<span class="descripcion_campo_class {{ $descripcion_campo_class }}"> {{ $guia2->transportista->getDocumentoNameComplete() }}</span>
</div>