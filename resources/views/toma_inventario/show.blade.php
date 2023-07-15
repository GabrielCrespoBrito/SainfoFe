@view_data([
'layout' => 'layouts.master',
'title' => $model->InvNomb,
'titulo_pagina' => $model->InvNomb,
'bread' => [ ['Toma Inventario', route('toma_inventario.index')] , ['Nueva'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','toma_inventario/create_mod.js']]
])


@slot('contenido')

@include('toma_inventario.partials.form', ['accion' => 'show' ])

@endslot

@endview_data
