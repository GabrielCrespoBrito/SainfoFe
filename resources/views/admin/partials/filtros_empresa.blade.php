@php
  $showLocal = $showLocal ?? true;
@endphp

<div class="row">

  <div class="{{ $showLocal ? 'col-md-7' : 'col-md-12' }} overflow-hidden">
    <select data-url-change="{{ route('admin.actions.change_empresa') }}" data-container="empresa_id_select" data-template="true" class="form-control input-sm select2" name="empresa_id" data-minimuminputlength="0">
      @foreach( $empresas as $empresa_id =>  $empresa )
      <option data-info="{{ json_encode($empresa) }}" data-class_name="{{ $empresa['active'] ? 'empresa-active' : 'empresa-inactive' }}" {{ $empresa['selected'] ? 'selected=selected' : '' }}  value="{{ $empresa_id }}"> {{ $empresa['nombre'] }} </option>
      @endforeach
    </select>
  </div>

  @if($showLocal)
  <div class="col-md-5">
    <select
      id="local"
      data-reloadtable="table"
      data-route="{{ route('admin.empresa.consult-locals') }}"
      name="local_id"
      class="form-control input-sm text-uppercase">
    </select>
  </div>
  @endif

  @isset($content)
    {!! $content !!}
  @endisset

</div>