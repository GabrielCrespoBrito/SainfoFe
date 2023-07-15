<div class="modal fade" id="modalActualizarProducto">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> Actualización de inventario </h4>
      </div>
    
    <div class="modal-body">

      <div class="tab-content">
        
        <form id="form-accion" method="get" action="{{ route('productos.update_inventarios') }}" autocomplete="off">

        <div class="row" id="demo">

          <div class="col-md-12" style="padding-left:20px;margin-bottom:20px">
            <label for=""> Local </label>
            <select type="text" requred name="local_inventario" class="form-control input-sm flat">
              <option value="todos">---- TODOS ----</option>
              @foreach( $locales as $local )
              <option value="{{ $local->LocCodi }}"> {{ $local->LocNomb }} </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-12" style="padding-left:20px;margin-bottom:20px">
            <label for=""> Fecha </label>
            <input name="fecha_inventario" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ datePeru('Y-m-d') }}" required="required"  class="form-control input-sm datepicker" data-default="{{ datePeru('Y-m-d') }}"  value="{{ datePeru('Y-m-d') }}" type="text">
          </div>

          <div class="col-md-12" style="padding-left:20px">
            <button class="btn btn-success btn-flat btn-block" type="submit"> 
              <span class="fa fa-save"></span> Actualizar </button>
          </div>

        </div>

        </form>
      </div>

    </div> <!-- modal-body  -->

    </div>

    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->