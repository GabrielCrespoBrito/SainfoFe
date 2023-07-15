@extends('layouts.master')

@section('titulo_pagina', 'Agregar documento de trabajo a usuario' )

@section('contenido')

<form action="{{ route('usuarios.asignar_documento.store') }}" method="post">
 @csrf

 <div class="row">

    <div class="form-group {{ $errors->first('usucodi') ? 'has-error' : '' }}  col-md-6">
      <label> Usuario *</label>
        <select class="form-control" name="usucodi">
          @if( $id_user == "all" )
            @foreach( $users as $user )
              <option value="{{ $user->id() }}">{{ $user->nombre() }}</option>
            @endforeach
          @else 
            <option value="{{ $users->id() }}">{{ $users->nombre() }}</option>
          @endif
        </select>
      @if( $errors->has('usucodi') )
        <span class="help-block">{{ $errors->first('id_user') }}</span>
      @endif
    </div>

    <div class="form-group {{ $errors->first('loccodi') ? 'has-error' : '' }}  col-md-6">
      <label> Local *</label>
        <select class="form-control" name="loccodi">
          @if( $id_local == "all" )
            @foreach( $locales as $local )
              <option value="{{ $local->LocCodi }}">{{ $local->LocNomb }}</option>
            @endforeach
          @else 
            <option value="{{ $locales->LocCodi }}">{{ $locales->LocNomb }}</option>
          @endif
        </select>
      @if( $errors->has('loccodi') )
        <span class="help-block">{{ $errors->first('id_local') }}</span>
      @endif
    </div>

</div>

<div class="row">

  <div class="form-group {{$errors->first('tidcodi') ? 'has-error' : '' }}  col-md-6">
    <label> Documento *</label>
      <select class="form-control" name="tidcodi">
        @foreach( $tipo_documentos as $tipo_documento )
          <option value="{{ $tipo_documento->TidCodi }}">{{ $tipo_documento->TidNomb }}</option>
        @endforeach
      </select>
    @if( $errors->has('tidcodi') )
      <span class="help-block">{{ $errors->first('tidcodi') }}</span>
    @endif
  </div>

  <div class="form-group {{$errors->first('sercodi') ? 'has-error' : '' }}  col-md-6">
    <label> Serie *</label>
      <input type="text" maxlength="4" class="form-control" name="sercodi" value="">
    @if( $errors->has('sercodi') )
      <span class="help-block">{{ $errors->first('sercodi') }}</span>
    @endif
  </div>

</div>

<div class="row">

  <div class="form-group {{$errors->first('numcodi') ? 'has-error' : '' }}  col-md-6">
    <label> Número *</label>
      <input type="text" maxlength="6 "class="form-control" name="numcodi" value="000000">
      @if( $errors->has('numcodi') )
        <span class="help-block">{{ $errors->first('numcodi') }}</span>
      @endif
  </div>

</div>


<div class="row">  
  <div class="form-group col-md-6">  
      <div class="checkbox">
        <label> <input type="checkbox" name="por_defecto" checked="checked" value="1">Por defecto</label>
    </div>
  </div>
  <div class="form-group col-md-6">  
      <div class="checkbox">
        <label> <input type="checkbox" name="estado" checked="checked" value="1">Estado</label>
    </div>
  </div>
</div>


  <div class="row">
    <div class="col-xs-12">
      <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar</button>
    </div>
  </div>

</form>

@endsection