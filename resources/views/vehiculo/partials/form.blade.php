@php
  $isCreate = !$model->exists;
	$url = $isCreate ? route('vehiculo.store') : route('vehiculo.update', $model->id) ;
@endphp

@include('partials.errors_html')

<form  method="post" action="{{ $url }}">		
  @csrf
  @if(!$isCreate)
    @method('PUT')
  @endif

  <div class="row">  

    <div class="form-group col-md-1 {{ $errors->has('EmpCodi') ? 'has-error' : '' }}">  
      <label>  Codigo </label>
      <input class="form-control input-sm" disabled  value="{{ $model->id }}">     
    </div>

    <div class="form-group col-md-4 {{ $errors->has('VehPlac') ? 'has-error' : '' }}">  
      <label> Placa (*) <span style="color: gray; font-size:.8em">Sin Espacio, ni Simbolos</span></label>
      <input class="form-control input-sm" required name="VehPlac" type="text" value="{{ old('VehPlac',$model->VehPlac) }}">
			@if( $errors->has('VehPlac')  )
			<span class="help-block">{{ $errors->first('VehPlac') }}</span>
			@endif    
    </div>

    <div class="form-group col-md-4 {{ $errors->has('VehMarc') ? 'has-error' : '' }}">  
      <label>  Marca (*) </label>
      <input class="form-control input-sm" required name="VehMarc" type="text" value="{{ old('VehMarc',$model->VehMarc) }}">     
   		@if( $errors->has('VehMarc')  )
			<span class="help-block">{{ $errors->first('VehMarc') }}</span>
			@endif
    </div>

    <div class="form-group col-md-3 {{ $errors->has('VehInsc') ? 'has-error' : '' }}">  
      <label>  Constancia de inscripción </label>
      <input class="form-control input-sm" name="VehInsc" type="text" value="{{ old('VehInsc',$model->VehInsc) }}">     
   		@if( $errors->has('VehInsc')  )
			<span class="help-block">{{ $errors->first('VehInsc') }}</span>
			@endif
    </div>

    <div class="form-group col-md-12">  
      <button class="btn-flat btn btn-primary" type="submit"> Guardar </button>
      <a  href="{{ route('empresa_transporte.index') }}" class="btn-flat btn btn-danger"> Salir </a>     
    </div>
</form>