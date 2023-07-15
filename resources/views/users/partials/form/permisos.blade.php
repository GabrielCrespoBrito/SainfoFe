<hr>

<p>
  <span class="fa fa-lock"></span> Permisos 
  <a href="#" class="btn btn-default btn-xs pull-right select-all" data-selected="0"> Seleccionar todo </a>
</p>

<div class="row">
  <div class="form-group col-md-12">
    @foreach( $permisos as $permiso )
      <label class="ml-x5 mr-x5 permission-checkbox"> <input name="permisos[]" value="{{ $permiso['id'] }}" class="permission-checkbox" type="checkbox"> {{ $permiso['nombre'] }} </label>
    @endforeach
  </div>
</div>




