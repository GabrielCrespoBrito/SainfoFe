<div class="row">
  <div class="col-xs-12">
    <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"></span> {{ $create ? 'Guardar' : 'Actualizar' }}</button>
    <a href="{{ route('admin.tipo_pago.index') }}" class="btn btn-danger btn-flat"> <span class="fa fa-back"></span> Cancelar </a>
  </div>
</div>