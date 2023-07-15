@extends('layouts.auth.auth')
@section('content')
  {!! $htmlContacto !!}
  <diV class="row">
    @if( auth()->guest() )
      <a class="btn" href="{{ route('login') }}"> Ingresar al sistema </a>
    @else
      <a class="btn" href="{{ route('home') }}"> Volver al inicio </a>
    @endif
  </diV>
@endsection