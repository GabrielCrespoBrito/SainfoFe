@component('components.modal', [ 'id' => 'modalComformidad', 'size' => 'modal-sm', 'title' => 'Guia Conformidad' ])
@slot('body')

<form action="{{ route('guia_ingreso.save_conformidad', '@@@@') }}" method="post">

  @csrf
  <!-- Group of default radios - option 1 -->
  <div class="custom-control custom-radio">
    <input 
      type="radio" 
      class="custom-control-input" 
      id="defaultGroupExample1" 
      name="e_conformidad" 
      value="{{ App\GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_PENDIENTE }}">
    <label class="custom-control-label" for="defaultGroupExample1">Pendiente</label>
  </div>

  <!-- Group of default radios - option 2 -->
  <div class="custom-control custom-radio">
    <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="e_conformidad" value="{{ App\GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_ACEPTADO }}">
    <label class="custom-control-label" for="defaultGroupExample2">Aceptado </label>
  </div>

  <!-- Group of default radios - option 3 -->
  <div class="custom-control custom-radio">
    <input type="radio" class="custom-control-input" id="defaultGroupExample3" name="e_conformidad" value="{{ App\GuiaSalida::ESTADO_CONFORMIDAD_TRASLADO_RECHAZADO }}">
    <label class="custom-control-label" for="defaultGroupExample3">Rechazado</label>
  </div>

  <div class="row">
    <div class="col-md-12">
    <textarea name="obs_traslado" class="form-control" rows="3" placeholder="Ingrese comentario"></textarea>
  </div>
  </div>

  <div class="row mt-x5">
    <div class="col-md-12">
      <button type="submit" class="btn btn-flat btn-primary" name="enviar"> Guardar </button>
    </div>
  </div>

</form>

@endslot
@endcomponent