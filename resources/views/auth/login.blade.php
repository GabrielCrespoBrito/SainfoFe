@extends('layouts.auth.auth')
@section('content')
  @php
  @endphp
  @include('auth.partials.form_user')
    <div class="row">
      <div class="col-md-12" style="margin-bottom: 10px">
        <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
        <a class="pull-right" href="{{ route('admin.login') }}">Area Adm </a>
      </div>
    </div>

@endsection


