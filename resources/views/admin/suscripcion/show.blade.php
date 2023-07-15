@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Suscripcion',
'titulo_pagina' => 'Suscripcion',
'bread' => [ ['Suscripcion'] ],
 'assets' => ['libs' => ['datatable'], 'js' => ['helpers.js','admin/suscripcion.js'] ]

])

@slot('contenido')
    @component('admin.suscripcion.partials.form', ['suscripcion' => $suscripcion ])
    @endcomponent
@endslot

@endview_data
