@php
$isAdmin = $isAdmin ?? true;
$routeStore = $isAdmin ? route('usuarios.store') : route('usuarios.store.owner');
$routeUpdate = $isAdmin ? route('usuarios.update') : route('usuarios.update.owner');
$tipo = $create ? '02' : $model->carcodi;
@endphp


<form  method="post" action={{ $create ? $routeStore : $routeUpdate }} id="form-usuario">

<input type="hidden" name="crear" value="true">
<input type="hidden" name="id" value="{{ $model->usucodi }}">

@include('users.partials.form.basic')

{{-- Contador --}}
<div class="row" style="margin-top:10px">
<div class="form-group col-md-12">
<label> Tipo de Usuario *</label>
<select {{ $create ? '' : 'disabled' }} class="form-control" name="tipo_usuario">
  <option value="02" {{ $tipo == '02' ? 'selected' : '' }}> Regular </option>
  <option value="11" {{ $tipo == '11' ? 'selected' : '' }}> Contador (Este Usuario solo podra acceder al area de contadores) </option>
</select>

@if($tipo != "11")
<div class="div-local">
@include('users.partials.form.locales')
</div>
@endif


@if($create)
<div class="div-permisos">
  @include('users.partials.form.permisos')
</div>
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
