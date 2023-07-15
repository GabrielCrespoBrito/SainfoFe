@extends('layouts.master')

@section('js')

<script src="{{ asset(mix('js/mix/helpers.js')) }}"> </script>
<script src="{{ asset('js/empresa/empresa.js') }}"> </script>

<script type="text/javascript">
  var error = [];
  @if($errors->count())     
    @foreach( $errors->all() as $error )
      error.push( "{{ $error }}" );
    @endforeach
  notificaciones(error, "error" , "Error" , false );
  @endif
  @if( session()->has('message')){
    notificaciones("{{session()->get('message') }}" , "success");
  }
  @endif
</script>
@endsection

@section('titulo_pagina', 'Crear Empresa')

@section('contenido')

{{-- Pagina de carga --}}

<div class="empresa-parametros">


<form action="{{ route('empresa.store') }}" id="form-create-empresa" method="post" enctype="multipart/form-data">

  @include('components.block_elemento')


  @csrf

  <div class="info-empresa"> 
    <div class="title-seccion"> Datos </div>   
    @include('empresa.partials.form.data_empresa')
  </div>

  <div class="info-parametros"> 
    <div class="title-seccion"> Información facturación </div>
    @include('empresa.partials.form.data_parametros')
  </div>


  <div class="acciones-div">
    <a href="{{ route('empresa.index') }}" class="btn link-salir btn-danger btn-flat pull-right">  Salir </a>
    <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar </button>
  </div>

</form>

</div>

@endsection

