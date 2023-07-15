@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Crear Permiso',
'titulo_pagina' => 'Crear Permiso',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js']]
])

@slot('contenido')
@include('partials.errores')

<form action="{{ route('admin.permissions.store') }}" method="post">

  @csrf


  <div class="row">

    <div class="form-group col-md-6">
      <label> Nombre </label>
      <input name="name" required="required" class="form-control" value="{{ old('name')  }}" type="text">
    </div>

    <div class="form-group col-md-6">
      <label> Descripción </label>
      <input name="descripcion" required="required" class="form-control" value="{{ old('descripcion') }}" type="text">
    </div>
  </div>


  <div class="form-group col-md-12">

    @if(!$roles->isEmpty())
    <label style="display: block">Asignar permiso a rol</label>
    @foreach ($roles as $role)
    <div class="checkbox">
      <label> <input name="roles[]" value="{{ $role->id }}" type="checkbox"> {{ $role->name }} </label>
    </div>
    @endforeach
    @endif

  </div>

  <div class="form-group col-md-12">
    <button type="submit" class="btn btn-success"> Guardar </button>
  </div>

</form>

@endslot

@endview_data