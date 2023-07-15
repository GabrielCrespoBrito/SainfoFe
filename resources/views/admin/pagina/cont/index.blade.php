@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Contabilidad Caracteristicas',
'titulo_pagina' => 'Contabilidad Caracteristicas',
'bread' => [ ['Inicio'] ],
'assets' => ['js' => ['helpers.js']]

])

@slot('contenido')
  <div 
    data-url-delete={{ route('admin.pagina.contabilidad-caracteristica.destroy', 'xxx') }} 
    data-url-search={{ route('admin.pagina.contabilidad-caracteristica.search') }} 
    data-url-store={{ route('admin.pagina.contabilidad-caracteristica.store') }} 
    data-url-update={{ route('admin.pagina.contabilidad-caracteristica.update', 'xxx') }} 
  id="root-cont"></div>
@endslot
@endview_data
