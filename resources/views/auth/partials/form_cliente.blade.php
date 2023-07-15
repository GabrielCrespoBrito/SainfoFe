<form style="display:{{ $form_2 ? 'block' : 'none'}}" id="cliente_form" action="{{ route('login_cliente') }}" method="POST">      
    @csrf
  <div class="form-group has-feedback {{ $errors->has('ruc') ? 'has-error' : '' }}">
    <input type="text" value="{{ old('ruc') }}" name="ruc" class="form-control" placeholder="RUC" required autofocus>
    <span class="fa fa-user form-control-feedback"></span>
    
    @if ($errors->has('ruc'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('ruc') }}</strong>
        </span>
    @endif
  </div>

  <div class="form-group has-feedback {{ $errors->has('password_cliente') ? 'has-error' : '' }}">
    <input type="password" name="password_cliente" class="form-control" required placeholder="Contraseña">
    <span class="fa fa-lock form-control-feedback"></span>

    @if ($errors->has('password_cliente'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('password_cliente') }}</strong>
        </span>
    @endif

  </div>

  <div class="row">
    <!-- /.col -->
    <div class="col-xs-4">
      <button type="submit" class="btn enviar btn-primary btn-block btn-flat enviar">Ingresar</button>
    </div>
    <!-- /.col -->
  </div>
</form>