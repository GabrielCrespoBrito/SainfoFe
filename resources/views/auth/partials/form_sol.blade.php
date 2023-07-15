<form method="POST" id="form-sol" autocomplete="off" action="{{ route('usuario.store_verificar_empresa') }}">

  <div class="step number-phone">
    <div class="form-group has-feedback">
      <input type="text" name="ruc" focus minlength="11" autofocus  maxlength="11" class="form-control" required placeholder="RUC">
      <span class="fa fa-numeric form-control-feedback"></span>
    </div>
  </div>

  <div class="step number-phone">
    <div class="form-group has-feedback">
      <input type="text" name="usuario_sol" class="form-control" required placeholder="Usuario SOL">
      <span class="fa fa-user form-control-feedback"></span>
    </div>
  </div>

  <div class="step number-phone">
    <div class="form-group has-feedback">
      <input type="password" name="clave_sol" class="form-control" required placeholder="Clave SOL">
      <span class="fa fa-lock form-control-feedback"></span>
    </div>
  </div>

  <div class="row validation-btn">
    <div class="col-xs-12 mb-2" style="margin-bottom:5px">
      <button type="submit" class="btn enviar btn-primary btn-flat ">Registrar </button>
      @include('partials.button_logout', ['clases' => 'pull-right' ])
    </div>
  </div>

</form>