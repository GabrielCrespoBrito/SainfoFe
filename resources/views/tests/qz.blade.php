@extends('layouts.master')

@section('bread')
<li> Bread </li>
@endsection

@section('css')
@endsection

@section('js')
<script src="{{ asset('plugins/qz/promise-polyfill-8.1.3.min.js') }}"></script>
<script src="{{ asset('plugins/qz/whatwg-fetch-3.0.0.min.js') }}"></script>
<script src="{{ asset('plugins/qz/qz.js') }}"></script>
<script src="{{ asset('plugins/qz/demo.js') }}"></script>
<script src="{{ asset('plugins/qz/script.js') }}"></script>
@endsection

@section('titulo_pagina', 'QZ PRUEBA')
@section('contenido')

<a href="#" id="enlaces" data-href_publica="{{ route('test.firma_publica') }}" data-href_privada="{{  route('test.firma_privada') }}">
FIRMAAA
</a>

@include('tests.print.partials.form')

@endsection
