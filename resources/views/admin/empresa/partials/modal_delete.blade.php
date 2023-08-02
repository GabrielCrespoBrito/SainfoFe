<div class="modal fade sainfo-modal-eliminar" id="modalDeleteEmpresa">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="border: 2px solid red;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" style="color: red">Eliminar </h4>
      </div>
      <div class="modal-body">
        <p> Esta seguro que desea eliminar esta empresa?</p>
        <p> Se eliminaran todos los registros asociados a esta empresa, ventas, productos, etc</p>
      </div>
      <div class="modal-footer">
        <form id="form-delete-empresa" data-url="{{ route('admin.empresa.delete','XXX') }}" method="post" action="#">
          @csrf
          <div class="row form-group">
            <div class="col-md-12">
              <input type="password" name="password" required class="form-control" placeholder="Contraseña maestra">
            </div>
          </div>
          
          <button class="btn pull-left btn-block btn-primary btn-flat"> 
            <span class="fa fa-check"> </span> Aceptar</button>

        </form>
      </div>
    </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
