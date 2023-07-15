@php
@endphp
<div class="parent-caracteristica {{ $isMaestro ? 'maestro' : 'empresa' }}">
  <div class="row mt-x10">
    
    <div class="{{ $isConsumo ? 'col-md-8' : 'col-md-12' }} ">
      <label> Nombre </label>
      <input name="nombre" {{ $canModify ? '' : 'disabled=disabled' }} required="required" class="form-control" value="{{ $caracteristica->caracteristica->nombre }}" />
    </div>

    @if($isConsumo)
    <div class="col-md-4">
      <label> Valor </label>
      <input name="value" required="required" class="form-control" value="{{ $caracteristica->value }}" />
    </div>
    @endif

  </div>

  <div class="row mt-x10">
    <div class="col-md-12">
      <input placeholder="Text Adicional" {{ $canModify ? '' : 'disabled=disabled' }} name="adicional" class="form-control" value="{{ $caracteristica->caracteristica->adicional }}" />
    </div>
  </div>

  <div class="row mt-x10">
    <div class="col-md-12">

      <a href="#" title="Guardar" data-url="{{ route('admin.plan-caracteristica.update', $caracteristica->id) }}" class="btn btn-primary btn-flat btn-caracteristica-update"> <span class="fa fa-save"></span> Guardar </a>

      <a href="#" title="Eliminar" data-url="{{ route('admin.plan-caracteristica.destroy', $caracteristica->id) }}" class="btn btn-danger btn-flat btn-caracteristica-delete {{  $isConsumo && $isMaestro ? 'disabled' : '' }}"> <span class="fa fa-trash"></span> Eliminar </a>

    </div>
  </div>
</div>