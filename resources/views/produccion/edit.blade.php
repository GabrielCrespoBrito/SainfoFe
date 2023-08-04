@view_data([
  'layout' => 'layouts.master' , 
  'title'  => $compra->CpaOper,
  'titulo_pagina'  => $compra->CpaOper, 
  'bread'  => [ ['Compras', route('compras.index')] , ['Modificar'] ],
  'assets' => ['libs' => ['datatable','select2','datepicker'],'js' => ['helpers.js','compras/mix/crud_mod.js', 'clientes/scripts.js']]
])
@slot('contenido')
  @include('compras.partials.form.form', ['accion' => 'edit' ])
@endslot

@endview_data