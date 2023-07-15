@php
  $isCreate = !$model->exists;
	$url = $isCreate ? route('empresa_transporte.store') : route('empresa_transporte.update', $model->id) ;
@endphp


<form  method="post" action="{{ $url }}">		
  @csrf
  @if(!$isCreate)
    @method('PUT')
  @endif

  <div class="row">  
    
    <div class="form-group col-md-3 {{ $errors->has('EmpRucc') ? 'has-error' : '' }}">  
      <label> Ruc (*)</label>
      <input class="form-control input-sm" required name="EmpRucc" type="text" value="{{ old('EmpRucc',$model->EmpRucc) }}">
			@if( $errors->has('EmpRucc')  )
			<span class="help-block">{{ $errors->first('EmpRucc') }}</span>
			@endif    
    </div>

    <div class="form-group col-md-6 {{ $errors->has('EmpNomb') ? 'has-error' : '' }}">  
      <label>  Nombre / Razón social (*) </label>
      <input class="form-control input-sm" required name="EmpNomb" type="text" value="{{ old('EmpNomb',$model->EmpNomb) }}">     
   		@if( $errors->has('EmpNomb')  )
			<span class="help-block">{{ $errors->first('EmpNomb') }}</span>
			@endif
    </div>
  
    <div class="form-group col-md-3 {{ $errors->has('mtc') ? 'has-error' : '' }}">  
      <label> <abbre title="aa">MTC</abbre> (*)</label>
      <input class="form-control input-sm" required name="mtc" type="text" value="{{ old('mtc',$model->mtc) }}">
			@if( $errors->has('mtc')  )
			<span class="help-block">{{ $errors->first('mtc') }}</span>
			@endif    
    </div>


    <div class="form-group col-md-12">  
      <button class="btn-flat btn btn-primary" type="submit"> Guardar </button>
      <a  href="{{ route('empresa_transporte.index') }}" class="btn-flat btn btn-danger"> Salir </a>     
    </div>
</form>