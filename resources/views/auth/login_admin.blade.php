@extends('layouts.auth.auth', [ 'urlBackground' => '/images/backgroundAdminLogin.jpg'])

@section('content')
@include('auth.partials.form_user', ['route' => route('admin.login') , 'background' => '/images/backgroundAdminLogin.jpg' ])

<div class="row">
  <div class="col-md-12" style="margin-bottom: 10px">
    <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
    <a class="pull-right" href="{{ route('login') }}">Area cliente </a>
  </div>
</div>

@endsection