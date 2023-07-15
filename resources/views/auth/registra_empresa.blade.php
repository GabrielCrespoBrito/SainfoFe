@extends('layouts.auth.auth')
@section('content')
    <h3 class="register-user-title verification"> Excelente </h3>
    <p class="register-user-subtitle"> Como ultimo paso necesitamos que introduzca los datos de su empresa </p>
  @include('auth.partials.form_empresa')
  @include('partials.form_logout')
@endsection