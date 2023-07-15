@php
  $isCreate = !$model->exists;
  $tipoDocumento = $model->getTipoDocumento();
	$url = $isCreate ? route('transportista.store') : route('transportista.update', $model->id) ;
@endphp

@include('partials.errors_html')

<form  method="post" action="{{ $url }}">		
  @csrf
  @if(!$isCreate)
    @method('PUT')
  @endif

  <div class="row">  

    <div class="form-group col-md-2 {{ $errors->has('TDocCodi') ? 'has-error' : '' }}">  
      <label style="display:block"> T.Documento (*)  
      {{-- <span class="btn btn-xs btn-default pull-right"><span class="fa fa-search"></span></span>  --}}
      </label>
        <select class="form-control input-sm" required name="TDocCodi">
          @foreach( $tiposdocumento as $codigo =>  $tipodocumento )
            <option {{ $tipoDocumento == $codigo ? 'selected': ''}} value="{{ $codigo }}"> {{ $tipodocumento }} </option>
          @endforeach
        </select>
    @if( $errors->has('TDocCodi')  )
			<span class="help-block">{{ $errors->first('TDocCodi') }}</span>
			@endif
    </div>


    <div class="form-group col-md-2 {{ $errors->has('TraRucc') ? 'has-error' : '' }}">  
      <label style="display:block"> Documento (*)  
      {{-- <span class="btn btn-xs btn-default pull-right"><span class="fa fa-search"></span></span>  --}}
      </label>
      <input class="form-control input-sm" required name="TraRucc" type="number" value="{{ old('TraRucc',$model->TraRucc) }}">     
       		@if( $errors->has('TraRucc')  )
			<span class="help-block">{{ $errors->first('TraRucc') }}</span>
			@endif
    </div>

    <div class="form-group col-md-4 {{ $errors->has('Nombres') ? 'has-error' : '' }}">  
      <label>  Nombres </label>
      <input class="form-control input-sm" required name="Nombres" type="text" value="{{ old('Nombres',$model->Nombres) }}">     
       		@if( $errors->has('Nombres')  )
			<span class="help-block">{{ $errors->first('Nombres') }}</span>
			@endif
    </div>

    <div class="form-group col-md-4 {{ $errors->has('Apellidos') ? 'has-error' : '' }}">  
      <label>  Apellidos (*) </label>
      <input class="form-control input-sm" required name="Apellidos" type="text" value="{{ old('Apellidos',$model->Apellidos) }}">     
      @if( $errors->has('Apellidos')  )
			<span class="help-block">{{ $errors->first('Apellidos') }}</span>
			@endif
    </div>    


  </div>

  <div class="row">
    
    <div class="form-group col-md-3 {{ $errors->has('TraLice') ? 'has-error' : '' }}">  
      <label style="display:block">  Licencia (*)  <a href="https://slcp.mtc.gob.pe/" target="_blank" class="btn btn-xs btn-default pull-right"><span class="fa fa-external-link"></span></a></label>
      <input class="form-control input-sm" required name="TraLice" type="text" value="{{ old('TraLice',$model->TraLice) }}">     
       		@if( $errors->has('TraLice')  )
			<span class="help-block">{{ $errors->first('TraLice') }}</span>
			@endif
    </div>

    <div class="form-group col-md-3 {{ $errors->has('TraTele') ? 'has-error' : '' }}">  
      <label> Telefono</label>
      <input class="form-control input-sm" name="TraTele" type="text" value="{{ old('TraTele',$model->TraTele) }}">     
       		@if( $errors->has('TraTele')  )
			<span class="help-block">{{ $errors->first('TraTele') }}</span>
			@endif
    </div>

    <div class="form-group col-md-6 {{ $errors->has('TraDire') ? 'has-error' : '' }}">  
      <label> Dirección </label>
      <input class="form-control input-sm" name="TraDire" type="text" value="{{ old('TraDire',$model->TraDire) }}">     
       		@if( $errors->has('TraDire')  )
			<span class="help-block">{{ $errors->first('TraDire') }}</span>
			@endif
    </div>
 
  </div>


  <div class="row">  
    <div class="form-group col-md-12">  
      <button class="btn-flat btn btn-primary" type="submit"> Guardar </button>
      <a  href="{{ route('transportista.index') }}" class="btn-flat btn btn-danger"> Salir </a>     
    </div>
  </div>
</form>