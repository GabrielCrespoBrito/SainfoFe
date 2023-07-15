@extends('landing.layout.master')

@section('content')
@include('landing.partials.slider')
@include('landing.partials.mission')
@include('landing.partials.services')
@include('landing.partials.planes')
@include('landing.partials.modulos')
@if(count($clientesGroup))
@include('landing.partials.clientes')
@endif

@if(count($testimonios))
@include('landing.partials.testimonios')
@endif

@endsection