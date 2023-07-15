@extends('layouts.master')

@section('bread')
<li> {{ $empresa->nombre() }} </li>
<li>  Subir certificado </li>
@endsection

@section('css')
@endsection

@section('js')
  @include('partials.errores')
@endsection

@section('titulo_pagina' , 'Certificado')

@section('contenido')

<div class="row">

  <div class="col-md-6">

    <div class="title"> Subir certificados </div>

    <form action="{{route('empresa.storeCertificado', $empresa->empcodi  ) }}" method="post" enctype="multipart/form-data">
      @csrf
      @include('empresa.partials.form.cert_inputs')
      <div class="acciones-div">
        <button type="submit" class="btn btn-block btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar </button>
      </div>
    </form>

  </div>

  <div class="col-md-6">

    <form action="{{route('empresa.checkCertificados', $empresa->empcodi  ) }}" method="post" enctype="multipart/form-data">
      @csrf
        <div class="title"> Verificar certificados </div>
        @include('empresa.partials.form.cert_inputs')
      </div>

      <div class="acciones-div">
        <button type="submit" class="btn btn-block btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar </button>
      </div>
  </form>
</div>
@endsection