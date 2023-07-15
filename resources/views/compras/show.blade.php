@view_data([
  'layout' => 'layouts.master' , 
  'title'  => $compra->CpaOper,
  'titulo_pagina'  => $compra->CpaOper, 
  'bread'  => [ ['Compras', route('compras.index')] , ['Mostrar'] ],
  'assets' => ['libs' => ['datatable','select2'],'js' => ['helpers.js', 'cajas/pagos.js' , 'compras/mix/crud_mod.js' ]],
  'components' => ['datetime']
])

@slot('contenido')
  @include('compras.partials.form.form', ['accion' => 'show' ])
@endslot

@endview_data
