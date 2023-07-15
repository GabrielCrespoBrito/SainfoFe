@php

$route = $area_admin ? 
route('admin.empresa.update_basic', $empresa->empcodi) : 
route('empresa.update_basic', $empresa->empcodi);

@endphp

<div class="empresa-parametros">

<form action="{{ $route }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}

<div class="row">

  <div class="form-group col-md-1">
    <div class="input-group">
      <input class="form-control input-sm" type="text" value="{{ $empresa->empcodi }}" readonly="readonly" disabled>
    </div>
  </div>

  <div class="form-group col-md-11">
    <div class="input-group">
      <span class="input-group-addon">Razón social</span>
      <input class="form-control input-sm" required="required" disabled name="nombre_empresa" type="text" value="{{  $empresa->EmpNomb }}">
    </div>
  </div>

</div>

<div class="row">
  <div class="form-group col-md-5">
    <div class="input-group">
      <span class="input-group-addon">RUC</span>
      <input class="form-control input-sm" required="required" name="ruc" type="text" value="{{  $empresa->EmpLin1 }}" disabled>
    </div>
  </div>

  <div class="form-group {{ $errors->has('nombre_comercial') ? 'has-error' : '' }} col-md-7">
    <div class="input-group">
      <span class="input-group-addon">Nombre Comercial</span>
      <input class="form-control input-sm" required="required" name="nombre_comercial" type="text" value="{{ old('nombre_comercial', $empresa->EmpLin5  ) }}">
    </div>
  </div>

</div>

<div class="row">
  <div class="form-group {{ $errors->has('direccion') ? 'has-error' : '' }} col-md-12">
    <div class="input-group">
      <span class="input-group-addon">Dirección</span>
      <input class="form-control input-sm" required="required" name="direccion" type="text" value="{{ old('direccion', $empresa->EmpLin2  ) }}">
    </div>
  </div>
</div>

<div class="row">

  <!-- Ubigeo  -->
  <div class="form-group {{ $errors->has('ubigeo') ? 'has-error' : '' }} col-md-5">
    <div class="input-group">
      <span class="input-group-addon">Ubigeo</span>
      @include('components.select2', [
      'id' => 'ubigeo',
      'data_id' => optional($empresa->ubigeo)->ubicodi,
      'value' => optional($empresa->ubigeo)->ubicodi,
      'text' => optional( $empresa->ubigeo)->completeName(),
      'url' => route('clientes.ubigeosearch'),
      'name' => 'ubigeo',
      'size' => ''
      ])
    </div>
  </div>
  <!-- /Ubigeo -->

  <div class="form-group {{ $errors->has('departamento') ? 'has-error' : '' }} col-md-3 pl-0">
    <div class="input-group">
      <span class="input-group-addon">Dep/Prov/Dis</span>
      <input class="form-control input-sm" required="required" disabled name="departamento" type="text" value="{{ old('departamento', $empresa->FE_DEPA  ) }}">
    </div>
  </div>

  <div class="form-group {{ $errors->has('provincia') ? 'has-error' : '' }} col-md-2 p-0">
      <input class="form-control input-sm" required="required" disabled name="provincia" type="text" value="{{ old('provincia', $empresa->FE_PROV) }}">
  </div>

  <div class="form-group {{ $errors->has('distrito') ? 'has-error' : '' }} col-md-2">
      <input class="form-control input-sm" required="required" disabled name="distrito" type="text" value="{{ old('distrito', $empresa->FE_DIST  ) }}">
  </div>

</div>

<div class="row">
  <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-6">
    <div class="input-group">
      <span class="input-group-addon">Email</span>
      <input class="form-control input-sm" required="required" name="email" type="text" value="{{ old('email', $empresa->EmpLin3  ) }}">
    </div>
  </div>
  <div class="form-group {{ $errors->has('telefonos') ? 'has-error' : '' }} col-md-6">
    <div class="input-group">
      <span class="input-group-addon">Telefonos</span>
      <input class="form-control input-sm" required="required" name="telefonos" type="text" value="{{ old('telefonos', $empresa->EmpLin4  ) }}">
    </div>
  </div>
</div>

<div class="row">
  <div class="form-group {{ $errors->has('rubro') ? 'has-error' : '' }} col-md-12">
    <div class="input-group">
      <span class="input-group-addon">Rubro</span>
      <input class="form-control input-sm" name="rubro" type="text" value="{{ old('rubro', $empresa->EmpLin6 ) }}">
    </div>
  </div>
</div>

    @include('empresa.partials.principal.botones')
  </form>
</div>
