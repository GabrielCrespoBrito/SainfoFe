@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Compras',
  'titulo_pagina'  => 'Compras', 
  'bread'  => [ ['Compras'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')

  @push('js') 
    @include('partials.errores')
  @endpush 

  @include('compras.partials.filter')
  
  @component('components.table', [ 'id' => 'datatable' , 'url' => route('compras.search')  , 'class_name' => 'sainfo-noicon size-9em', 'thead' => [ '#' , 'N° Doc' , 'T.D' , 'Fecha', 'Proveedor', 'Condicion' , 'Usuario', 'Mon', 'Importe', 'Pago' ,'Saldo' ,'SdCant', 'Est. Almacen' , ''] ])
  @endcomponent

  @include('partials.modal_eliminate', ['url' => route('compras.destroy' , 'XX') ])

@endslot  

@endview_data