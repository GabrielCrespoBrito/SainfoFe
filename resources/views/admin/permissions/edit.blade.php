@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Modificar Permiso',
'titulo_pagina' => 'Modificar Permiso',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js']]
])

@slot('contenido')
@include('partials.errores')

<form action="{{ route('admin.permissions.update', $permission->id) }}" method="post">

  @csrf
  @method('PUT')

  <div class="row">

    <div class="form-group col-md-6">
      <label> Nombre </label>
      <input name="name" required="required" class="form-control" value="{{ $permission->name  }}" type="text">
    </div>    
    <div class="form-group col-md-6">
      <label> Descripción </label>
      <input name="descripcion" required="required" class="form-control" value="{{ $permission->descripcion  }}" type="text">
    </div>
  </div>


  <div class="form-group col-md-12">
    <button type="submit" class="btn btn-success"> Guardar </button>
  </div>

</form>


@endslot

@endview_data