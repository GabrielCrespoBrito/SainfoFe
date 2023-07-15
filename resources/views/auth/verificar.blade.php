@extends('layouts.auth.auth')

@section('content')
    <h3 class="register-user-title verification"> Bienvenido <span class="username"> {{ auth()->user()->usulogi }}</span> solo falta un paso para verificar su cuenta </h3>
  @include('auth.partials.form_verificar')
  @include('partials.form_logout')

@endsection