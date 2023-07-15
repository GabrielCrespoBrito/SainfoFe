@view_data([
  'layout' => 'layouts.master' , 
  'title'  => 'Perfil',
  'titulo_pagina'  => 'Perfil', 
  'bread'  => [ ['Perfil'] ],
  'assets' => ['libs' => ['datatable'],'js' => ['helpers.js','compras/index.js']]
])

@slot('contenido')
  
  <form method="post" action="{{ route('usuarios.perfil.update') }}">

    <p class="title-form-perfil"> Información basica </p>
    @csrf

    <div class="row">

      <div class="form-group {{ $errors->has('usunomb') ? 'has-error' : '' }} col-md-6">
        <label> Nombre </label>
        <input required name="usunomb" maxlength="145" value="{{ old('usunomb',$user->nombre) }}"  class="form-control"/>
        @if ($errors->has('usunomb'))
          <span class="help-block">
              <strong>{{ $errors->first('usunomb') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group {{ $errors->has('usutele') ? 'has-error' : '' }} col-md-6">
        <label> Telefono </label>
        <input required name="usutele" maxlength="45" value="{{ old('usutele',$user->telefono) }}"  class="form-control"/>    
        @if ($errors->has('usutele'))
          <span class="help-block">
              <strong>{{ $errors->first('usutele') }}</strong>
          </span>
        @endif
      </div>

    </div> 

    <div class="row">

      <div class="form-group {{ $errors->has('usudire') ? 'has-error' : '' }} col-md-6">
        <label> Dirección </label>
        <input name="usudire" maxlength="145" value="{{ old('usudire',$user->direccion) }}"  class="form-control"/>    
        @if ($errors->has('usudire'))
          <span class="help-block">
              <strong>{{ $errors->first('usudire') }}</strong>
          </span>
        @endif      
      </div>

      <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-6">
        <label> Email </label>
        <input required name="email" type="email" maxlength="200" value="{{ old('email',$user->email) }}"  class="form-control"/>    
        @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif
      </div>

    </div> 

    <div class="row">
      <div class="col-md-12">
        <button type="submit" class="btn btn-success"> Guardar </button> 
      </div>
    </div>


  </form>

<hr>


<form method="post" action="{{ route('usuarios.password.update') }}">
  @csrf
	<p class="title-form-perfil"> Cambiar contraseña </p>

  <div class="row">

    <div class="form-group col-md-4" {{ $errors->has('last_password') ? 'has-error' : '' }}>
      <label> Contraseña actual  </label>
      <input type="password" name="last_password" required="required" class="form-control"/>    
      @if ($errors->has('last_password'))
          <span class="help-block bg-red">
              <strong>{{ $errors->first('last_password') }}</strong>
          </span>
      @endif
    </div>

    <div class="form-group col-md-4" {{ $errors->has('password') ? 'has-error' : '' }}>
      <label> Nueva contraseña  </label>
      <input type="password" name="password" minlength="8" required="required" class="form-control"/>    
      @if ($errors->has('password'))
          <span class="help-block bg-red">
              <strong>{{ $errors->first('password') }}</strong>
          </span>
      @endif      
    </div>

    <div class="form-group col-md-4" {{ $errors->has('password_confirmation') ? 'has-error' : '' }}>
      <label> Repetir contraseña  </label>
      <input type="password" required="required" minlength="8" name="password_confirmation" class="form-control"/>    
      @if ($errors->has('password_confirmation'))
          <span class="help-block bg-red">
              <strong>{{ $errors->first('password_confimation') }}</strong>
          </span>
      @endif            
  	</div>

  </div> 

  <div class="row">
  	<div class="col-md-12">
  		<button type="submit" class="btn btn-success"> Guardar </button> 
  	</div>
  </div>


</form>

@endslot  

@endview_data