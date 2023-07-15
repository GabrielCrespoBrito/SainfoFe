<div class="modal modal-seleccion fade" id="modalSelectCliente">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Buscar cliente </h4>
      </div>
      <div class="modal-body">
        <div class="botones_div">

        <a class="btn pull-left btn-success btn-flat elegir_elemento">
          <span class="fa fa-check"> </span> Aceptar</a>

        <a target="_blank" href="{{ route('clientes.index') }}" class="btn pull-left btn-default btn-flat">
          <span class="fa fa-pencil"> </span> Nuevo</a>

        <a class="btn pull-left btn-default btn-flat">
          <span class="fa fa-check"> </span> Modificar</a>

        </div>        

        <div class="clientes_select">          
          <table style="width:100%" class="table table-bordered sainfo-table" id="datatable-clientes" >
          <thead>
            <tr>
              <td> Código </td>
              <td> Tipo Doc </td>              
              <td> RUC </td>
              <td> Razón social </td>    
            </tr>
          </thead>
          </table>
        </div>
      </div>

    </div>


    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
