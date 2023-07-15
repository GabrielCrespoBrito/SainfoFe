<div class=" div-option" id="div-edit-masivo" style="display:none">
  <div class="row">
    <div class="col-md-4">
        <div class="btn-group btn-group-tipo">
          <button type="button" data-value="1" class="btn accion-btn accion-elegir-tipo tipo-agregar   btn-default">
          {{-- <i class="fa fa-align-left"></i>  --}}
          Aumentar </button>
          <button type="button" data-value="0" class="btn accion-btn accion-elegir-tipo tipo-disminuir btn-default">
          {{-- <i class="fa fa-align-center"></i>  --}}
          Disminuir </button>

        </div>
    </div>

    <div class="col-md-4">
      <div class="btn-group btn-group-campo">
        <button type="button" data-value="costo" class="btn accion-btn accion-elegir-campo campo-costo btn-default">
        Costo </button>

        <button type="button" data-value="margen" class="btn accion-btn accion-elegir-campo campo-costo btn-default">
        Margen Gan. </button>

        <button type="button" data-value="precios_min" class="btn accion-btn accion-elegir-campo campo-costo btn-default">
        Precios Min </button>        
      </div>
    </div>

    <div class="col-md-2">
      <div class="input-group input-group-value">
        <span class="input-group-addon"> % </span>
        <input type="number" max="100" min="1" value="" class="form-control input-sm" name="valor">
      </div>
    </div>

    <div class="col-md-2">
      <button type="submit" data-url="{{ route('unidad.actualizacion_masiva') }}" class="btn btn-success btn-sm btn-flat btn-actualizar"><i class="fa fa-align-center"></i> Actualizar </button>
    </div>
  </div>
</div>