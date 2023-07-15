<div class="row">
  
  <div class="col-md-5">
    <select class="form-control input-sm" name="tipo">
      <option value="maestro" {{ $showEmpresa ? '' : 'selected=selected' }}> Maestro </option>
      <option value="empresa" {{ $showEmpresa ? 'selected=selected' : '' }}> Empresa </option>
    </select>
  </div>

  <div class="col-md-7 empresa_parent overflow-hidden" style="display:{{ $showEmpresa ? 'block' : 'none' }}">
    <select class="form-control input-sm select2" name="empresa_id" data-minimuminputlength="0">
      @foreach( $empresas as $empresa_id => $empresa )
      <option {{ $empresa['selected'] ? 'selected=selected' : '' }} value="{{ $empresa_id }}"> {{ $empresa['nombre'] }} </option>
      @endforeach
    </select>
  </div>

</div>