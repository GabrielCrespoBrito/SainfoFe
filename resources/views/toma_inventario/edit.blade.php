@view_data([
'layout' => 'layouts.master',
'title' => 'Modificar ' . $model->InvNomb,
'titulo_pagina' => 'Modificar ' . $model->InvNomb,
'bread' => [ ['Toma Inventario', route('toma_inventario.index')] , [$model->InvNomb] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','toma_inventario/create_mod.js']]
])


@slot('contenido')

@include('toma_inventario.partials.form', ['accion' => 'edit' ])

@include('components.specific.modal_productos', [
'nuevo_producto' => false,
'title_modal' => 'Seleccionar Productos',
'btn_aceptar_text' => 'Seleccionar',
'fields_after' =>'<input type="checkbox" class="input-select-all">',
])

@endslot

@endview_data