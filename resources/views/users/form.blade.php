@php
$isAdmin = $isAdmin ?? true;
$routeStore = $isAdmin ? route('usuarios.store') : route('usuarios.store.owner');
$routeUpdate = $isAdmin ? route('usuarios.update') : route('usuarios.update.owner');
@endphp


<form  method="post" action={{ $create ? $routeStore : $routeUpdate }} id="form-usuario">

<input type="hidden" name="crear" value="true">
<input type="hidden" name="id" value="{{ $model->usucodi }}">

@include('users.partials.form.basic')
@include('users.partials.form.locales')

@if($create)
  @include('users.partials.form.permisos')
@endif

<div class="row mt-x10">
<div class="col-xs-12">
  {{-- <a class="btn btn-primary btn-flat send_user_info"> <span class="fa fa-save"></span> Guardar</a> --}}
  <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar</button>
  <button  data-dismiss="modal" aria-label="Close" type="button" class="btn btn-danger pull-right btn-flat">Salir  </button>  
</div>
</div>

<!-- /.col -->
</form>
