
@extends('layouts.master')

@section('bread')
<li>  Rol </li>
@endsection

@section('css')
@endsection

@section('js')
@endsection

@section('titulo_pagina', 'Roles')

@section('contenido')

	<form action="{{ route('usuarios.assign_role' , $user->usucodi ) }}" method="post">

	@csrf

	<div class="form-group col-md-12">
    @if(!$roles->isEmpty()) 
        <label style="display: block">Asignar permiso a rol</label>
        @foreach ($roles as $role) 
          <div class="checkbox">
            <label> <input name="roles[]" {{ $user->hasRole($role) ? 'checked=checked' : '' }}  value="{{ $role->id }}"  type="checkbox"> {{ $role->name }}</label>
          </div>
        @endforeach
    @endif
	</div>

	<div class="form-group col-md-12">
	    <button type="submit" class="btn btn-success"> Guardar </button>
	</div>

	</form>

@endsection