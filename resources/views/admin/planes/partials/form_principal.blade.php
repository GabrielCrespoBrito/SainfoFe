<form action="{{ route('admin.plan.update', $plan->id ) }}" method="post" id="form-principal">

@csrf

<div class="row">

  <div class="col-md-8">
    <label> Nombre </label>
    <input name="codigo" required="required" class="form-control" value="{{ $plan->codigo }}" />
  </div>

  <div class="col-md-4">
    <label> Duración </label>
    <select class="form-control" name="duracion_id">
      @foreach( $duraciones as $duracion )
      <option {{$plan->duracion_id == $duracion->id ? 'selected=selected' : ''}} value="{{ $duracion->id }}"> {{ $duracion->nombre }}</option>

      @endforeach
    </select>
  </div>

</div>


<div class="row mt-x10">

<div class="col-md-6">
<label> Descuento </label>
<div class="input-group">
  <span class="input-group-addon">$</span>
  <input type="text" name="descuento_value" value="{{ $plan->descuento_value }}" class="form-control">


</div>
</div>

<div class="col-md-6">
<label> Descuento </label>
<div class="input-group">
  <span class="input-group-addon">%</span>
  <input type="text" name="descuento_porc" value="{{ $plan->descuento_porc }}" class="form-control">
</div>
</div>

</div>

<div class="row mt-x10">

  <div class="col-md-4">
    <label> Base </label>
    <input name="base" class="form-control" value="{{ $plan->base }}" />
  </div>

  <div class="col-md-4">
    <label> IGV </label>
    <input name="igv" data-porc="{{ $igv_porc }}" readonly="readonly" class="form-control" value="{{ $plan->igv }}" />


  </div>

  <div class="col-md-4">
    <label>Total</label>
    <input name="total" class="form-control" value="{{ $plan->total }}" />
  </div>

</div>

<div class="row mt-x10">

  <div class="col-md-4">
    <button class="btn btn-primary btn-flat send" type="submit"> Guardar </button>
  </div>

  @if( $plan->isEmpresa() )
  <div class="col-md-8">
    <label class="pull-right"> <input type="checkbox" value="0" name="update_by_parent" {{ $plan->update_by_parent ? '' : 'checked' }}> Manejo Individual </label>

  </div>
  @endif

</div>

</form>

