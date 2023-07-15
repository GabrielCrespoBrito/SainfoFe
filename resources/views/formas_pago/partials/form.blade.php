@php
$isCreate = $accion == "create";
$isContado = $isCreate ? true : $model->isContado();
$route = $isCreate ? route('formas-pago.store') : route('formas-pago.update', $model->id );
$showComponent = $isCreate ? false : $model->isCredito();

@endphp

@include('partials.errors_html')

<form accept-charset="UTF-8" class="form-forma-pago" method="post" action="{{ $route }}">
  @csrf

  @if( !$isCreate )
  @method('PUT')
  @endif

  <div class="row">
    <div class="col-md-8 form-group">
      <label> Nombre * </label>
      <input type="text" class="form-control text-upper text-uppercase" required placeholder="Nombre" name="connomb" value="{{ old('connomb', $model->descripcion) }}">
    </div>

    <div class="col-md-4 form-group">
      <label> Tipo </label>
      <select class="form-control" required name="contipo" value="{{ old( 'condias', $model->condias ?? 0) }}">
        <option {{ $isContado ? 'selected=selected' : ''   }} value="C">CONTADO</option>
        <option {{ $isContado ? '' : 'selected=selected'   }} value="D">CREDITOS</option>
      </select>
    </div>

    <!-- <div class="col-md-4 form-group">
      <label> Diás * </label>
      <input type="number" class="form-control" required name="condias" value="{{ old( 'condias', $model->condias ?? 0) }}">
    </div> -->
  </div>

  <!-- Componente  -->



  <div class="container-component-letras" style="display:{{ $showComponent ? 'block' : 'none' }}">

    <hr>

    <div class="row">
      <div class="col-md-4 form-group">
        Días <span class="btn btn-xs btn-primary add-letra"> <span class="fa fa-plus"> </span> </span>
      </div>
    </div>


    <div class="container-letras col-md-12" style="margin-bottom: 20px;">
      @foreach( $model->dias as $dia )
      <div class="row component-letras">
        <div class="input-group col-md-3">
          <input type="hidden" name="PgoCodi" value="{{ $dia->PgoCodi }}" class="form-control">
          <input type="number" required name="PgoDias" value="{{ $dia->PgoDias }}" class="form-control">
          <span class="btn input-group-addon delete-btn" style="color: red;"><i class="fa fa-minus"></i></span>
        </div>
      </div>
      @endforeach
    </div>

  </div>

  <div class="row">
    <div class="col-md-12">
      <button class="btn btn-primary btn-save btn-flat"> Guardar </button>
      <a style="padding-left:10px" class="pl-2 link-index" href="{{ route('formas-pago.index') }}"> Cancelar </a>
    </div>
  </div>


</form>