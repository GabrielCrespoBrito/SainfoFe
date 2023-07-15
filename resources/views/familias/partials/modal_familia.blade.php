<div class="modal modal-seleccion fade" id="modalFamilia">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        
     

        <h4 class="modal-title">Nueva Familia</h4>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="form-group col-md-3">  
            <div class="input-group">
              <span class="input-group-addon">Codigo</span>
                <input class="form-control input-sm" name="id_familia" type="text">     
            </div>
          </div>

          <div class="form-group col-md-9">  
            <div class="input-group">
              <span class="input-group-addon">Nombre</span>
                <input class="form-control input-sm text-uppercase" name="nombre" type="text">     
            </div>
          </div>
        </div>        



        <div class="row">
          <div class="form-group col-md-12">  
            <div class="input-group">
              <span class="input-group-addon">Grupos</span>              
                <select class="form-control input-sm" name="id_grupo">     
                  @foreach($grupos as $grupo)
                    <option value="{{ $grupo->GruCodi }}" data-ultimo_id="{{ $grupo->ultimo_id() }}">{{ $grupo->GruNomb }}</option>
                  @endforeach
                </select>
            </div>
          </div>
        </div>     



        <div class="row">
          <div class="col-md-12">
          <button type="button" class="btn btn-success save"> Guardar </button>     
          <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"> Cancelar </button>
          </div>
        </div>

      </div>

    </div>


    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

