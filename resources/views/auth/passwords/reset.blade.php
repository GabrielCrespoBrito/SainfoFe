@extends('layouts.auth.auth')
@section('content')


<form method="POST" id="form-send" action="{{ route('password.request') }}">
    @csrf


  <h3 class="register-user-title"> Cambiar contraseña </h3>
    <input type="hidden" name="token" value="{{ $token }}">
  
	<div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
    <input type="email" value="{{ old('email', $email ) }}" required autofocus name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Dirección de correo de su cuenta">
    <span class="fa fa-envelope form-control-feedback"></span>
    @if ($errors->has('email'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
  </div>

  <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
    <input type="password"  value="" required minlength="8"  name="password" class="form-control  {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Nueva contraseña">
    <span class="fa fa-lock form-control-feedback"></span>
    @if ($errors->has('password'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
  </div>

  <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
    <input type="password"  value="" required minlength="8" name="password_confirmation" class="form-control  {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Confirmar contraseña">
    <span class="fa fa-lock form-control-feedback"></span>
    @if ($errors->has('password_confirmation'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
    @endif
  </div>

    <div class="form-group row mb-0">
        <div class="col-md-12">
            <button type="submit" class="btn btn-send btn-primary btn-block btn-flat">
                
                <span class="estado-cargando" style="display:none"><span class="fa fa-spinner fa-spin"></span>  Cargando </span> 
                
                <span class="estado-default"> Cambiar contraseña </span> 
                


            </button>
        </div>
    </div>

</form>

@endsection


