@view_data([
  'layout' => 'layouts.master',
  'title'  => 'Registrar Inventario',
  'titulo_pagina' => 'Registrar Inventario',
  'bread'  => [ ['Toma Inventario', route('toma_inventario.index')] , ['Nueva'] ],
  'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','toma_inventario/create_mod.js']]
])


@slot('contenido')

  @include('toma_inventario.partials.form', [ 'accion' => 'create' ])
  
  @include('components.specific.modal_productos', [
    'nuevo_producto' => false, 
    'title_modal' => 'Seleccionar Productos',
    'btn_aceptar_text' => 'Seleccionar',
    'fields_after' =>'<input type="checkbox" class="input-select-all">',
    ])

@endslot  

@endview_data