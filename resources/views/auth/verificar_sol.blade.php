@extends('layouts.auth.auth')
@section('content')
    <h3 class="register-user-title verification"> Ingresar Clave Sol </h3>
    <p class="register-user-subtitle"> Ahora necesitamos su <strong>CLAVE SOL</strong> para poder enviar los documentos electronicos a la SUNAT. De esta manera estara dando su alta a SAINFO como su <strong>PES</strong> en <strong>SUNAT</strong> </p>
  @include('auth.partials.form_sol')
  @include('partials.form_logout')
@endsection