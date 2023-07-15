@php
  $route = $route ?? route('login');
  $demo = $demo ?? false;
@endphp



<form style="display:block" action="{{ $route }}" id="erp_form" method="POST">
  @if($demo)
  <div class="info-login-demo">
    <div class="data"> Usuario: <span class="value"> {{ $username }} </span> </div>
    <div class="data"> Contraseña: <span class="value"> {{ $password }} </span> </div>
  </div>
  @endif


  @csrf
<div class="form-group has-feedback {{ $errors->has('usulogi') ? 'has-error' : '' }}">
  <input type="text" value="{{ old('usulogi') }}" name="usulogi" style="text-transform:uppercase" class="form-control" placeholder="USUARIO" required autofocus>


  <span class="fa fa-user form-control-feedback"></span>
</div>

<div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
  <input type="password" name="password" current-password class="form-control" required placeholder="CONTRASEÑA">
  <span class="fa fa-lock form-control-feedback"></span>
</div>

<div class="row">
  <div class="col-xs-8">
    <div class="checkbox icheck">
      <label> <input type="checkbox">Recordarme</label>
    </div>
  </div>
  <!-- /.col -->
  <div class="col-xs-4">
    <button type="submit" class="btn enviar btn-primary btn-block btn-flat">Ingresar</button>
  </div>
  <!-- /.col -->
</div>
</form>