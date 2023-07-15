@view_data([
'layout' => 'layouts.master_admin',
'title' => "Clientes",
'titulo_pagina' => "Clientes",
'bread' => [ ['Inicio'], 'js' => ['helpers.js'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js','admin/mix/documentos_mix.js' ]]
])

@slot('contenido')
  <div 
    data-url-delete={{ route('admin.pagina.clientes.destroy', 'xxx') }} 
    data-url-search={{ route('admin.pagina.clientes.search') }} 
    data-url-store={{ route('admin.pagina.clientes.store') }} 
    data-url-update={{ route('admin.pagina.clientes.update', 'xxx') }} 
    id="landing.cliente.root">
  </div>
@endslot



@endview_data