@extends('layouts.auth.auth', [ 'urlBackground' => '/images/backgroundAdminLogin.jpg'])

@section('content')
@include('auth.partials.form_user', ['route' => route('admin.login') , 'background' => '/images/backgroundAdminLogin.jpg' ])

<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px">
    <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
    <a class="pull-right" href="{{ route('login') }}">Area cliente </a>
  </div>
</div>

    @if( config('auth.register_user') )
    <div class="row box-register-cuenta"
      <div class="col-md-12 ">
        <p href="#">¿No tienes cuenta?</p>
        <a class="register" href="{{ route('register') }}">CREA <strong>UNA CUENTA GRATIS!<strong></a>
      </div>
    </div>
    @endif

@endsection