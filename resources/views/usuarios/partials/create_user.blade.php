
<form id="form-usuario" onsubmit="return false;" autocomplete="off">

    <input type="hidden" name="crear" value="true">
    
    <div class="row">

    <div class="form-group col-md-4">
        <label> Código * </label>
        <input name="codigo" required="required" class="form-control" value="{{ $users->last()->usucodi }}" type="text">
    </div>

    <div class="form-group col-md-8">
        <label> Cargo *</label>
        <select name="cargo"  required="required" class="form-control">

            <?php $first = true; ?>
            @foreach( $cargos as $cargo )
            @if( ! $cargo->isMaster() )
                <option {{ $first ? 'selected=selected' : '' }} value="{{ $cargo->CarCodi }}"> {{ $cargo->CarNomb }} </option>
            <?php $first = false;  ?>
            @endif
            @endforeach

        </select>
    </div>

    </div>

    <div class="row">

    <div class="form-group col-md-4">
        <label> Usuario *</label>
        <input name="usuario"  required="required" class="form-control" type="text">
    </div>

    <div class="form-group col-md-8">
        <label> Nombre Completo *</label>
        <input name="nombre"  required="required" class="form-control" type="text">
    </div>
    
    </div>


    <div class="row">

    <div class="form-group col-md-6">
        <label> Contraseña *</label>
        <input name="password"  required="required" class="form-control" type="password">
    </div>

    <div class="form-group col-md-6">
        <label for="password_confirmation"> Repetir Contraseña *</label>
        <input id="password_confirmation" name="password_confirmation"  required="required" class="form-control" type="password">
    </div>
    
    </div>


    <div class="row">

    <div class="form-group col-md-4">
        <label> Teléfono </label>
        <input name="telefono" class="form-control" type="text">
    </div>

    <div class="form-group col-md-8">
        <label> Dirección </label>
        <input name="direccion" class="form-control" type="text">
    </div>
    
    </div>



    <div class="row">

			<div class="col-xs-12">
					<a class="btn btn-primary btn-flat send_user_info"> <span class="fa fa-save"></span> Guardar</a>
			</div>

    </div>

</form>

