@extends('layouts.auth.auth')
@section('content')


<form method="POST" id="form-send" action="{{ route('password.email') }}">
    @csrf

  <h3 class="register-user-title"> Cambio de contraseña </h3>

  <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
    <input type="email" value="{{ old('email') }}" required name="email" class="form-control  {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Dirección de correo de su cuenta">
    <span class="fa fa-envelope form-control-feedback"></span>
    @if ($errors->has('email'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
  </div>


    <div class="form-group row mb-0">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-send btn-block btn-flat">
                
                <span class="estado-cargando" style="display:none"><span class="fa fa-spinner fa-spin"></span>  Enviando email... </span> 
                <span class="estado-default"> Enviar enlace de cambio de contraseña</span> 
                
            </button>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-12">
            <a href="{{ route('login') }}" class="btn btn-block btn-flat"> <span class="fa fa-"> </span> Volver al login 
            </a>
        </div>
    </div>

</form>

@endsection

