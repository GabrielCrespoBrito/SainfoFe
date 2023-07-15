<div class="row">
    <div class="col-md-12 mt-x10">
    <div class="form-clear-tables">
      <form method="GET" action="{{ route('admin.empresa.change-status', [ 'id' => $empresa->empcodi] ) }}">
        @csrf
        <div class="row">
          <div class="col-md-12">
            <p class="title">Cambiar Estado  </p>
          </div>
          <div class="col-md-12 mt-x10">
            <button 
              type="submit" 
              name="enviar" 
              class="btn btn-{{$empresa->isActive() ? 'danger' : 'success' }} btn-flat"> 
              {{ $empresa->isActive() ? 'Desactivar Empresa' : 'Activar Empresa' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>