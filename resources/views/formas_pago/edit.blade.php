@view_data([
'layout' => 'layouts.master' ,
'title' => 'Modificar Forma de pago',
'titulo_pagina' => 'Modificar Forma de pago',
'bread' => [ ['Modificar Forma de pago', route('formas-pago.index')] , ['Modificar'] ],
'assets' => ['js' => ['helpers.js','forma_pago/create.js' ]]
])

@slot('contenido')
@include('formas_pago.partials.form', ['accion' => 'edit' ])
@endslot

@endview_data