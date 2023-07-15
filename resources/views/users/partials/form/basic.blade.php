<div class="row">

<div class="form-group col-md-5">
<label> Nombre de Usuario *</label>
<input name="usuario" required="required" {{ $create ? '' : 'disabled'  }} value="{{ $model->usulogi }}"  class="form-control" type="text">
</div>

<div class="form-group col-md-7">
<label> Nombre Completo *</label>
<input name="nombre" required="required" class="form-control" value="{{ $model->usunomb }}" type="text">
</div>

</div>


<div class="row">

<div class="form-group col-md-6">
<label> Contraseña *</label>
<div class="input-group">
<input type="password" name="password" value="{{ $model->usucla2 }}" required class="form-control">
<span class="cursor-pointer input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
</div>
</div>

<div class="form-group col-md-6">
<label for="password_confirmation"> Repetir Contraseña *</label>

<div class="input-group">
<input id="password_confirmation" name="password_confirmation" value="{{ $model->usucla2 }}"  type="password" required class="form-control">
<span class="cursor-pointer input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
</div>
</div>
</div>


<div class="row">
<div class="form-group col-md-6">
<label> Teléfono </label>
<input name="telefono" class="form-control" value="{{ $model->usutele }}" type="text">
</div>

<div class="form-group col-md-6">
<label> Email * </label>
<input name="email" class="form-control" value="{{ $model->email }}"  type="email">
</div>
</div>

<div class="row">

<div class="form-group col-md-12">
<label> Dirección </label>
<input name="direccion" class="form-control" value="{{ $model->usudire }}" placeholder="Av. República del 777 Urb. Huaquillay - Comas" type="text">
</div>

</div>
