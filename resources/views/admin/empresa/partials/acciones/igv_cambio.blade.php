<div class="row">
  <div class="col-md-12 mt-x10">
    <div class="form-clear-tables">
      <form method="GET" action="{{ route('admin.empresa.change-aplicacion-igv', [ 'id' => $empresa->empcodi] ) }}">
        @csrf
        <div class="row">
          
          <div class="col-md-12">
            <p class="title">Cambiar Aplicación de IGV de Productos  </p>
          </div>

          <div class="row pl-x10 pr-x10">

            <div class="col-md-1">
              <button 
                type="submit" 
                name="enviar" 
                class="btn btn-flat btn-default"> <span class="fa fa-save"></span> Aceptar 
                </button>
            </div>

            <div class="col-md-11">
              <select 
                name="aplicar_igv" 
                class="form-control"> 
                <option value="1"> Con IGV </option>
                <option value="0"> Sin IGV </option>
              </select>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
</div>