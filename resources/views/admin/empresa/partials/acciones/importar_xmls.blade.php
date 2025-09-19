  <div class="col-md-12 mt-x10 pl-0">
    <div class="form-clear-tables">
      <form method="GET" action="{{ route('admin.empresa.importar-xmls', [ 'id' => $empresa->empcodi] ) }}">
        @csrf
        <div class="row">
          
          <div class="col-md-12">
            <p class="title">Importar Xmls  </p>
          </div>


            <div class="col-md-1">
              <button 
                type="submit" 
                name="enviar" 
                class="btn btn-flat btn-default"> <span class="fa fa-save"></span> Aceptar 
                </button>
            </div>

            <div class="col-md-5">
              <input 
                name="path_xmls" 
                class="form-control"
                required
                placeholder="Ruta de los Xmls, ejemplo: /home/user/xmls"> 
            </div>

            <div class="col-md-3">
              <input name="desde_numeros" class="form-control" placeholder="01-F001-400,03-B001-350,09-F001-200,RA-202509-000010">
            </div>

            <div class="col-md-3">
              <input name="tipo_documentos" class="form-control" required placeholder="01,03,09,RA">
            </div>

        </div>
      </form>
    </div>
  </div>