<div class="row">
  <div class="col-md-12 mt-x10">
    <div class="form-clear-tables">
      <form method="POST" action="{{ route('admin.empresa.reset_data', [ 'id' => $empresa->empcodi] ) }}">
        @csrf
        <div class="row">
          <div class="col-md-12">
            <p class="title">Limpiar Información </p>
          </div>
          <div class="col-md-12 mt-x10">
            <button type="submit" name="enviar" class="btn btn-primary btn-flat">Limpiar información </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
