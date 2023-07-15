@view_data([
'layout' => 'layouts.master_admin' ,
'title' => 'Permisos',
'titulo_pagina' => 'Permisos',
'bread' => [ ['Inicio'] ],
'assets' => ['libs' => ['datepicker','select2','datatable'],'js' => ['helpers.js']]
])

@slot('contenido')
    @include('partials.errores')


<div class="col-md-12 acciones-div ww">
  <a href="{{ route('admin.permissions.create')}}" class="btn btn-primary pull-right btn-flat"><span class="fa fa-plus"></span> Nuevo Permiso</a>
  <a href="{{ route('usuarios.mantenimiento') }}" class="btn btn-default btn-flat pull-left"> <span class="fa fa-users"></span> Users</a>

  <a href="{{ route('admin.roles.index') }}" class="btn btn-default btn-default pull-left btn-flat">
    <span class="fa fa-shield"></span> Roles</a>

</div>

<!-- table v-t sainfo-table sainfo-noicon sainfo-table ventas-d table_ventas_index -->
<div class="col-md-12 col-xs-12" style="overflow-x: scroll;">
  <table class="table sainfo-table" id="datatable">
    <thead>
      <tr>
        <td> Permiso </td>
        <td> Descripción </td>
        <td> Acciones </td>
      </tr>
    </thead>
    <tbody>
      @foreach ($permissions as $permission)
      <tr>
        <td>{{ $permission->name }}</td>
        <td>{{ $permission->descripcion }}</td>
        <td>
          <a href="{{ route('admin.permissions.edit', $permission->id)  }}" class="btn btn-xs btn-info pull-left" style="margin-right: 3px;">Edit</a>
          <form method="post" action="{{ route('admin.permissions.destroy', $permission->id)}}">
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