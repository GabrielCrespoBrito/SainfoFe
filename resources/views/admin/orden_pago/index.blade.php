@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Administracion - Ordenes de pago',
'titulo_pagina' => 'Administracion - Ordenes de pago',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','planes/ordenes.js' ]]
])

@slot('contenido')
@include('admin.orden_pago.partials.filtros')
<hr/>

@component('admin.orden_pago.partials.list')
@endcomponent

@endslot

@endview_data




