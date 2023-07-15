@php
	$url = $create ? route('vendedor.store') : route('vendedor.update', $model->Vencodi );
@endphp

@include('partials.errors_html')

<form method="post" action="{{ $url }}" class="form_principal factura_div focus-green" id="form_principal">		
  @csrf
  @if(!$create)
    @method('PUT')
  @endif

    <div class="row">

    <div class="form-group col-md-3">
      <label for="nombre_completo">Nombre Completo (*)</label>
      <input type="text" class="form-control" required id="nombre_completo" name="vennomb" value="{{ old('vennomb', $model->vennomb) }}">
    </div>

    <div class="form-group col-md-3">
      <label for="email">Email </label>
      <input type="text" class="form-control" id="email" name="venmail" value="{{ old('venmail', $model->venmail) }}">
    </div>

    <div class="form-group col-md-3">
      <label for="telefono"> Telefono </label>
      <input type="text" class="form-control" id="telefono" name="ventel1" value="{{ old('ventel1', $model->ventel1) }}">
    </div>    

    <div class="form-group col-md-3">
      <label for="usucodi"> Usuario </label>
      <select name="usucodi" id="usucodi" class="form-control">
      <option value> - SELECCIONAR USUARIO -- </option>        
      @foreach( $usuarios as $usuario )
        <option {{ $model->usucodi == $usuario->usucodi ? 'selected' : '' }}  value="{{ $usuario->usucodi }}"> {{ $usuario->usulogi }} </option>        
      @endforeach
      </select>
    </div>  

  </div>

  <div class="row">
    
    <div class="form-group col-md-12">
      <label for="code">Dirección</label>
      <textarea cols="3" type="text" class="form-control" id="code" name="vendire" value="{{ old('vendire', $model->vendire) }}">{{ old('vendire', $model->vendire) }}
      </textarea>
    </div>
    
  </div>

  <div class="row">
    <div class="form-group col-md-12" style="margin-top:10px">
      <button class="btn btn-flat btn-primary" type="submit" value="Guardar"> Guardar </button>
      <a href="{{ route('vendedor.index') }}" class="btn btn-danger btn-flat"> Salir </a>
    </div>

  </div>

</form>