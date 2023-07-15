@extends('layouts.master')

@section('titulo_pagina', 'Agregar empresa' )

@section('contenido')

<form action="{{ route('usuarios.empresa.store') }}" method="post">
 @csrf

 <div class="row">
    <div class="form-group {{ $errors->first('id_user') ? 'has-error' : '' }}  col-md-12">
      <label> Usuario *</label>
        <select class="form-control" name="id_user">
          @if( $id_user == "all" )
            @foreach( $user as $u )
              <option value="{{ $u->id() }}">{{ $u->usucodi . ' | '  . $u->usulogi }} </option>
            @endforeach
          @else 
            <option value="{{ $user->id() }}">{{ $user->usucodi . ' | '  . $user->usulogi  }}</option>
          @endif
        </select>
      @if( $errors->has('id_user') )
        <span class="help-block">{{ $errors->first('id_user') }}</span>
      @endif
    </div>

    <div class="form-group {{ $errors->has('id_empresa') ? 'has-error' : '' }} col-md-12">
      {{-- <select class="form-control" name="id_empresa">
        @if( $id_empresa == "all" )
        @foreach( $emps as $empresa )
          <option value="{{ $empresa->empcodi }}">{{ $empresa->nombreFull() }}</option>
        @endforeach
        @else
          <option value="{{ $emps->empcodi }}">{{ $emps->nombreFull() }}</option>
        @endif
      </select> --}}
      @if( $id_empresa == "all" )
     <select class="form-control" name="id_empresa">
        @foreach( $emps as $empresa )
          <option value="{{ $empresa->empcodi }}">{{ $empresa->nombreFull() }}</option>
        @endforeach
      </select>
      @else
          {{-- <option value="{{ $emps->empcodi }}">{{ $emps->nombreFull() }}</option> --}}
        <input name="id_empresa" type="hidden" value="{{ $emps->empcodi }}">
        <p class="form-control">
          {{ $emps->nombreFull() }}
         </p>
      @endif

      @if($errors->has('id_empresa'))
        <span class="help-block">{{ $errors->first('id_empresa') }}</span>
      @endif
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      
      <button type="submit" class="btn btn-primary btn-flat"> 
        <span class="fa fa-save"></span> Guardar
      </button>

      <a class="pull-right btn btn-danger" href="{{ route('home') }}"> 
        Cancelar  
      </a>

    </div>
  </div>

</form>

@endsection