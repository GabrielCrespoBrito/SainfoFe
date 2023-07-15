@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Roles',
'titulo_pagina' => 'Roles',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js']]
])

@slot('contenido')

<div class="col-md-12 acciones-div ww">
  <a href="{{ route('admin.roles.create') }}" class="btn btn-primary pull-right btn-flat"> <span class="fa fa-plus"></span> Nuevo </a>
  <a href="{{ route('usuarios.mantenimiento') }}" class="btn btn-default btn-flat pull-left"> <span class="fa fa-user"></span> Usuarios @TODO</a>
</div>

<div class="col-md-12 col-xs-12" style="overflow-x: scroll;">

  <table class="table table-bordered sainfo-table table-striped">
    <thead>
      <tr>
        <th>Role</th>
        <th>Permisos</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($roles as $role)
      <tr>
        <td>{{ $role->name }}</td>
        <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>
        <td>
          <a href="{{ route('admin.roles.edit', $role->id)  }}" class="btn btn-xs btn-info pull-left" style="margin-right: 3px;">Edit</a>
          <form method="post" action="{{ route('admin.roles.destroy', $role->id)}}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-xs btn-danger"> Eliminar </button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endslot

@endview_data