<div class="modal fade" id="ModalPrivilegioUsuario">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Privilegio usuarios</h4>
      </div>
      <div class="modal-body">


    <form id="form-usuario-privilegios" onsubmit="return false;">

      <input type="hidden" name="codigo" value="">
      
      <div class="row">

        <div class="form-group col-md-12">
          <label> Nombre * </label>
          <input name="nombre" disabled="disabled" class="form-control" value="FONSECA">
        </div>

        <div class="form-group col-md-12">
          
          <label> Empresa </label>
          
          <select name="empresa" required="required" class="form-control">           
            @foreach( $emps as $empresa )
              <option value="{{ $empresa->empcodi }}"> {{ $empresa->EmpNomb }} </option>
            @endforeach
          </select>

        </div>

      </div>


      <div class="row">

        <div class="form-group col-md-12">
          
          <label> Menus </label>
          
          <select name="menu" required="required" class="form-control">           
              @foreach( $menus as $menu )
                <option value="{{ $menu->menCodi }}"> {{ $menu->menTitu }} </option>
              @endforeach
          </select>

        </div>

        <div class="form-group col-md-12 group-privilegios">          

          <p> Privilegios </p>

          
          <div class="col-md-12" data-id="1">

            <div class="row">

              @foreach( $privilegios as $privilegio )
              <div class="checkbox col-md-12 col-sm-12">
                <label> <input type="checkbox">{{ $privilegio->privDesc }}</label>
              </div>
              @endforeach

            </div> 


          </div>  


        </div>

      </div>

      <div class="row">
        <div class="col-xs-12">

          <a class="btn btn-primary btn-flat send_priv_info"> <span class="fa fa-save"></span> Guardar</a>
          
        </div>
      </div>


        <!-- /.col -->
    </form>

    </div>

    </div>


    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
