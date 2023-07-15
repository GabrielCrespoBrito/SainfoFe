@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Clientes Logos',
'titulo_pagina' => 'Clientes Logos',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','admin/mix/documentos_mix.js' ]]
])

@slot('contenido')
  <div id="root-client"></div>
@endslot

@endview_data