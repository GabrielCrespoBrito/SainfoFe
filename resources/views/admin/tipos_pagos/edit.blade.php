@view_data([
'layout' => 'layouts.master_admin',
'title' => 'Modificar Tipo de Pago',
'titulo_pagina' => 'Modificar Tipo de Pago',
'bread' => [ [ 'Tipos de Pago', route('admin.tipo_pago.index')] , ['Modificar'] ],
])

@slot('contenido')

<form action="{{ $route }}" method="post">
  @csrf
  @include('admin.tipos_pagos.partials.form')
  @include('admin.tipos_pagos.partials.save')
</form>

@endslot

@endview_data