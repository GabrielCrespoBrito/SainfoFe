@view_data([
'layout' => 'layouts.master' ,
'title' => 'Unidades manteniento',
'titulo_pagina' => 'Unidades manteniento',
'bread' => [ ['Compras'] ],
'urls' => [[ 'url_save' , route('unidad.updatePrices', '@@') ]],
'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','unidad/mix/index_mix.js']]
])
@slot('contenido')
@include('unidad.partials.options')
@php
  $ultimoCosto = auth()->user()->isAdmin() ? sprintf('Ult.Cos <a href="%s" class="btn btn-xs btn-default"> <span class="fa fa-refresh"></span> </a>', route('productos.ultimo_costo')) : 'Ult.Cos';

@endphp

<div class=" col-md-12 col-xs-12 content_ventas div_table_content no_pl" style="overflow-x: scroll;">

@component('components.table', [ 'id' => 'datatable', 'attributes' => ['data-id' => $producto_id, 'data-dsoles' => $decimales_soles, 'data-ddolares' => $decimales_dolares ,'data-update_massive' => route('unidad.actualizacion_masiva_manual'),  'data-route' => route('reportes.compra_venta',[ 'producto' => 'xxx' ]) ], 'url' => route('unidad.search') , 'class_name' => 'sainfo-noicon size-9em',
'thead' => [ 
  'Codigo' ,
  'Lista.P',
  'Unidad' ,
  'Producto' ,
$ultimoCosto,
'Mon',
'Costo $' ,
'Costo S/',
'Marg',
'Prec.V S/',
'Prec.V $',
'Prec.Min S./',
'Prec.Min USD', 
'Porc. Coms.', 
'', 
'Mov'

]])
@endcomponent
</div>

@endslot
@endview_data