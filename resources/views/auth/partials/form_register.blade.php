<form method="POST" id="form-register"  action="{{ route('register') }}">
    @csrf

  <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
    <input type="text" value="{{ old('name') }}" name="name" class="form-control" autocomplete="off"  style="text-transform:uppercase" placeholder="Nombre de Usuario" required autofocus>

    <input type="hidden" value="{{ $id ?? '' }}" name="plan_id">

    <span class="fa fa-user form-control-feedback"></span>
  </div>

  <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
    <input type="email" value="{{ old('email') }}" required name="email" autocomplete="off"  class="form-control" placeholder="CORREO ELECTRONICO">

    <span class="fa fa-envelope form-control-feedback"></span>
  </div>

  <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
    <input type="password" value="" minlength="8" required name="password" autocomplete="off" class="form-control" placeholder="CONTRASEÑA">
    <span class="fa fa-lock form-control-feedback"></span>
  </div>

  <div class="form-group has-feedback">
    <input type="password" value="" minlength="8" required name="password_confirmation" autocomplete="off"  class="form-control" placeholder="REPETIR CONTRASEÑA">

    <span data-action="show-hide-password" class="fa fa-lock form-control-feedback"></span>

  </div>

 <div class="row">
    <div class="col-xs-12">
      <div class="checkbox icheck">
        <label> <input required name="aceptar_politica" type="checkbox"> 
            Acepto los 
            <a target="_blank" href="{{ get_setting('url_terminos_condiciones', "#") }}"> Términos y Condiciones</a> 
            y la 
            <a target="_blank" href="{{ get_setting('url_politica_privacidad', "#") }}">Política de Privacidad </a> 
            de Sainfo 
         </label> 
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 mb-2" style="margin-bottom:5px">
      <button type="submit" class="btn enviar btn-primary btn-block btn-flat">Crear cuenta</button>
    </div>
  </div>

 <div class="row back-login">
    <div class="col-xs-12" style="margin-bottom:10px">
      <a href="{{ route('login') }}"> Ya tienes cuenta?, inicia sesión</a> 
    </div>
  </div>

</form>