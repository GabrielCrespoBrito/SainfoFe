@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Roles',
'titulo_pagina' => 'Roles',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js']]
])


@slot('contenido')

<form action="{{ route('admin.roles.store') }}" method="post">
  @csrf
  <div class="row">
    <div class="form-group col-md-12">
      <label> Nombre </label>
      <input name="name" required="required" class="form-control" value="{{ old('name')  }}" type="text">
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-4">
      @foreach ( $permissions as $permission )
      <div class="checkbox">
        <label> <input name="permissions[]" value="{{ $permission->id }}" type="checkbox"> {{ ucfirst($permission->name) }}</label>
      </div>
      @endforeach
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-success"> Guardar </button>
    </div>
  </div>

</form>

@endslot

@endview_data