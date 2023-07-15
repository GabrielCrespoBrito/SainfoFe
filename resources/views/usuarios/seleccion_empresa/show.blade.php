@extends('layouts.master')

@section('titulo_pagina')

<span class="caja_number">
  {{ $empresa->nombre() }} 
</span>

<span class="caja_number">
  {{ $user->nombre() }} 
</span>

@endsection

@section('js')
  @include('partials.errores')
@endsection


@section('contenido')

   <div class="row">
      <div class="col-md-12">
        <a href="{{ route('usuarios_documentos.create' , ['id_user' => $user->usucodi , 'id_empresa' => $empresa->id()]) }}" class="pull-right btn btn-default "> <span class="fa fa-copy"></span> Agregar Documento </a>
      </div>
    </div>  




  <form action="{{ route('usuarios.empresa.delete' , $id ) }}" method="post">
   @csrf	
   <div class="row">
      <div class="form-group {{ $errors->first('usucodi') ? 'has-error' : '' }} col-md-12">
        <label> Usuario *</label>
        <input disabled value="{{ $user->usucodi }} - {{ $user->usulogi }}" class="form-control" type="text">
        @if($errors->has('usucodi'))
          <span class="help-block">{{ $errors->first('usucodi') }}</span>
        @endif
      </div>
      <div class="form-group col-md-12">
        <label> Empresa *</label>
        <input disabled value="{{ $empresa->nombre() }}" class="form-control" type="text">
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <button type="submit" class="btn btn-danger btn-flat"> <span class="fa fa-trash"></span> Eliminar</button>

        <a class="btn btn-default pull-right btn-flat" href="{{ route('usuarios.mantenimiento') }}"> <span class="fa fa-reply"></span> Volver</a>

      </div>
    </div>
  </form>

@endsection