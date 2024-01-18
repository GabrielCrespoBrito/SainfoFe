@php
	$url = $create ? route('zonas.store') : route('zonas.update', $model->ZonCodi );
@endphp

@include('partials.errors_html')

<form method="post" action="{{ $url }}" class="form_principal factura_div focus-green" id="form_principal">		
  @csrf
  @if(!$create)
    @method('PUT')
  @endif

    <div class="row">

    <div class="form-group col-md-3">
      <label for="ZonCodi">Codigo  (*)</label>
      <input type="text" class="form-control" {{ $create ? '' : 'disabled' }} required id="ZonCodi" name="ZonCodi" value="{{ old('ZonCodi', $model->ZonCodi) }}">
    </div>

    <div class="form-group col-md-9">
      <label for="Nombre"> Nombre </label>
      <input type="text" class="form-control" required id="Nombre" name="ZonNomb" value="{{ old('ZonNomb', $model->ZonNomb) }}">
    </div>    

  </div>


  <div class="row">
    <div class="form-group col-md-12" style="margin-top:10px">
      <button class="btn btn-flat btn-primary" type="submit" value="Guardar"> Guardar </button>
      <a href="{{ route('zonas.index') }}" class="btn btn-danger btn-flat"> Salir </a>
    </div>

  </div>

</form>