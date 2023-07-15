@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Testimonios de Clientes',
'titulo_pagina' => 'Testimonios de Clientes',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')
  <div 
    data-url-delete={{ route('admin.pagina.testimonios.destroy', 'xxx') }} 
    data-url-search={{ route('admin.pagina.testimonios.search') }} 
    data-url-search-cliente={{ route('admin.pagina.clientes.search') }} 
    data-url-store={{ route('admin.pagina.testimonios.store') }} 
    data-url-update={{ route('admin.pagina.testimonios.update', 'xxx') }} 
  id="root-testimonios"></div>
@endslot

@endview_data