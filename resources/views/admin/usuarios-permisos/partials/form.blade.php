<form action="{{ route('admin.usuario-permisos.update' , $user->usucodi ) }}" method="post">
  @csrf
  
  {{-- Botones para guardar seleccionar todos --}}
	<div class="row">
    <div class="col-md-12">
      
      <a href="#" class="seleccion seleccion-inicial btn btn-default btn-sm btn-flat pull-right"> <span class="fa fa-refresh"> </span> Restablecer Selecciones Originales </a>

      <a href="#" data-action="0" class="seleccion btn btn-default btn-sm btn-flat pull-right"> <span class="fa fa-square-o"> </span> Quitar selección  </a>

      <a href="#" data-action="1" class="seleccion btn btn-default btn-sm btn-flat pull-right"> <span class="fa fa-check-square-o"> </span> Seleccionar todo  </a>

    </div>
  </div>

  {{-- Permisos --}}
	<div class="permissions-div">

    @foreach ($permisos_group as $group_name => $permisos) 

      <div class="row row-group">
          
        <div class="title"> {{ $group_name }}  </div>
        
        @foreach ($permisos as $permiso) 
        <div class="col-md-3">
          <div class="checkbox">
          @php
            $hasPermissionTo = $user->hasPermissionTo($permiso);
          @endphp
            <label> <input name="permisos[]" data-default="{{ $hasPermissionTo }}" {{ $hasPermissionTo ? 'checked=checked' : '' }} value="{{ $permiso->id }}"  type="checkbox"> {{ $permiso->nameRead() }}</label>
          </div>
        </div>
        @endforeach
      </div>
    @endforeach

	</div>

  {{-- Boton de guardar --}}
  <div class="row">
    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-success btn-flat"> Guardar </button>
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-danger pull-right btn-flat"> Cancelar </a>
    </div>
  </div>

</form>