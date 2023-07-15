@php
	$url = $create ? route('cuenta.store') : route('cuenta.update', $model->CueCodi );
@endphp

@include('partials.errors_html')

<form method="post" action="{{ $url }}" class="form_principal factura_div focus-green" id="form_principal">		
  @csrf
  @if(!$create)
    @method('PUT')
  @endif

  <div class="row">
    <div class="form-group col-md-2">
      <label for="code">Codigo</label>
      <input type="text" class="form-control" id="code" readonly="readonly" name="CueCodi" value="{{ $model->CueCodi }}">
    </div>

    <div class="form-group col-md-2">
      <label for="banco">Banco</label>
      <select class="form-control" id="banco" required name="BanCodi">
        @foreach( $bancos as $banco )
        <option {{ $model->BanCodi == $banco->bancodi ? 'selected=selected' : '' }} value="{{ $banco->bancodi }}"> {{ $banco->bannomb }} </option>  
        @endforeach
      </select>
    </div>

    <div class="form-group col-md-4">
      <label for="nro_cuenta">Nro Cuenta (*)</label>
      <input type="text" class="form-control" required id="nro_cuenta" name="CueNume" value="{{ old('CueNume', $model->CueNume) }}" placeholder="Nro de Cuenta">
    </div>


    <div class="form-group col-md-2">
      <label for="exampleInputEmail1">Moneda</label>
      <select class="form-control" id="banco" required name="MonCodi">
        @foreach( $monedas as $moneda )
        <option {{ $model->MonCodi == $moneda->moncodi ? 'selected=selected' : '' }} value="{{ $moneda->moncodi }}"> {{ $moneda->monabre }} </option>  
        @endforeach
      </select>
    </div>

    <div class="form-group col-md-2">
      <label for="detraccion">Detraccion</label>
      <select class="form-control" id="detraccion" name="Detract">
        <option {{ $model->Detract ? 'selected=selected' : '' }} value="1"> Si </option>  
        <option {{ $model->Detract ? '' : 'selected=selected' }} value="0"> No </option>  
      </select>
    </div>

  </div>

  <div class="row">
    <div class="form-group col-md-12" style="margin-top:10px">
      <button class="btn btn-flat btn-primary" type="submit" value="Guardar"> Guardar </button>
      <a href="{{ route('cuenta.index') }}" class="btn btn-danger btn-flat"> Salir </a>
    </div>

  </div>

</form>