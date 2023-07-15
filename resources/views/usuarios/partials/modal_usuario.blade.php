@php
$isAdmin = $isAdmin ?? true;
$routeStore = $isAdmin ? route('usuarios.store') : route('usuarios.store.owner');
$routeUpdate = $isAdmin ? route('usuarios.update') : route('usuarios.update.owner');
@endphp
<div class="modal fade" id="ModalNuevoUsuario" data-urlstore="{{ $routeStore }}" data-urlupdate="{{ $routeUpdate }}">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Nuevo usuario *</h4>
      </div>
      <div class="modal-body">


        <form id="form-usuario" onsubmit="return false;">

          <input type="hidden" name="crear" value="true">
          <input type="hidden" name="id" value="">

          <div class="row">

            <div class="form-group col-md-4">
              <label> Usuario *</label>
              <input name="usuario" required="required" class="form-control" type="text">
            </div>

            <div class="form-group col-md-8">
              <label> Nombre Completo *</label>
              <input name="nombre" required="required" class="form-control" type="text">
            </div>

          </div>


          <div class="row">

            <div class="form-group col-md-6">
              <label> Contraseña *</label>
              <div class="input-group">
                <input type="password" name="password" required class="form-control">
                <span class="cursor-pointer input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
              </div>
            </div>

            <div class="form-group col-md-6">
              <label for="password_confirmation"> Repetir Contraseña *</label>

              <div class="input-group">
                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control">


                <span class="cursor-pointer input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
              </div>

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
            <div class="form-group col-md-12">
              <label> Email * </label>
              <input name="email" class="form-control" type="email">
            </div>
          </div>

          @if(isset($roles) )
          <div class="row">
            <div class="col-md-12"> <strong> Asigar rol </strong> </div>
          </div>

          <div class="row">
            <div class="form-group col-md-12">
              @foreach( $roles as $role )
              <div class="checkbox">
                <label>
                  <input value="{{ $role->id }}" data-name="{{ $role->name }}" class="roles" name="roles[]" type="checkbox"> {{ ucfirst($role->name) }}
                </label>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          <div class="row">
            <div class="col-xs-12">
              <a class="btn btn-primary btn-flat send_user_info"> <span class="fa fa-save"></span> Guardar</a>
            </div>
          </div>


          <!-- /.col -->
        </form>

      </div>

    </div>


  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

