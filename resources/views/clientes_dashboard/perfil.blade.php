@extends('layouts.master_cliente')
{{-- user.perfil --}}
@section('bread')
<li>  Perfil </li>
@endsection

@section('css')
{{-- @add_libreria('datatable|css') --}}
@endsection

@section('js')
{{-- @add_js('helpers.js') --}}
{{-- @add_js('empresa/index.js') --}}
{{-- @add_libreria('datatable|js') --}}
@endsection

@section('titulo_pagina', 'Perfil')

@section('contenido')

@if(Session::has('message'))

<div class="callout callout-success">
  <h4> {{ Session::get('message') }} </h4>
</div>

@endif


<form action="{{ route('cliente_administracion.update_password') }}">

  <div class="row">

    <div class="form-group col-md-6">
      <label> Nombre  </label>
      <input readonly="readonly" value="{{ $cliente->PCNomb }}"  class="form-control"/>    
    </div>

    <div class="form-group col-md-6">
      <label> Documento  </label>
      <input readonly="readonly" value="{{ $cliente->PCRucc }}"  class="form-control"/>    
    </div>

  </div> 

  <div class="row">

    <div class="form-group col-md-6">
      <label> Dirección  </label>
      <input readonly="readonly" value="{{ $cliente->PCDire }}"  class="form-control"/>    
    </div>

    <div class="form-group col-md-6">
      <label> Ubigeo  </label>
      <input readonly="readonly" value="{{ $cliente->PCDist }}"  class="form-control"/>    
    </div>

  </div> 


</form>

<hr>


<form method="post" action="{{ route('cliente_administracion.update_password') }}">

	@csrf

	<p> Cambiar contraseña </p>

  <div class="row">

    <div class="form-group col-md-4" {{ $errors->has('last_password') ? 'has-error' : '' }}>
      <label> Contraseña anterior  </label>
      <input type="password" name="last_password" required="required" value=""  class="form-control"/>    
      @if ($errors->has('last_password'))
          <span class="invalid-feedback">
              <strong>{{ $errors->first('last_password') }}</strong>
          </span>
      @endif
    </div>

    <div class="form-group col-md-4" {{ $errors->has('password') ? 'has-error' : '' }}>
      <label> Nueva contraseña  </label>
      <input type="password" name="password" required="required" value=""  class="form-control"/>    
      @if ($errors->has('password'))
          <span class="invalid-feedback">
              <strong>{{ $errors->first('password') }}</strong>
          </span>
      @endif      
    </div>

    <div class="form-group col-md-4" {{ $errors->has('password_confirmation') ? 'has-error' : '' }}>
      <label> Repetir contraseña  </label>
      <input type="password" required="required" value="" name="password_confirmation" class="form-control"/>    
      @if ($errors->has('password_confirmation'))
          <span class="invalid-feedback">
              <strong>{{ $errors->first('password_confimation') }}</strong>
          </span>
      @endif            
  	</div>

  </div> 

  <div class="row">
  	<div class="col-md-12">
  		<button type="submit" class="btn btn-success"  > Guardar </button> 
  		 		
  		<a href="{{ route('cliente_administracion.index') }}" class="btn btn-danger pull-right"> Volver </a>  		

  	</div>
  </div>


</form>

@endsection