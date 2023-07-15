@php
 $image_header = true;
@endphp

@extends('landing.layout.master')
@section('js')
  <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
  @include('landing.partials.navegacion', [ 'links' => [ ['src' => route('landing.contact'), 'text' => 'Contacto'  ] ] ])
  <!-- Contacts-->
  <section class="section section-md">
    <div class="container">
      <h2>Contacto</h2>
        @if( session('message') )
        <div class="alert alert-success" role="alert">
          {{ session('message') }}
      </div>
        @endif
        
      <div class="row row-40">
        @include('landing.partials.contact.datos')
        @include('landing.partials.contact.form')
      </div>
    </div>
  </section>
@stop