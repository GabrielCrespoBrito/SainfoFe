<div class="modal modal-seleccion fade" id="modalSunatRespuesta">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Generar guia de salida</h4>
      </div>
      <div class="modal-body" style="overflow: hidden;">
        
        <div class="botones_div">

          <a class="btn pull-left btn-success btn-flat aceptar_guia">
            <span class="fa fa-check"> </span> Aceptar</a>

          <a class="btn pull-left btn-danger btn-flat salir_guia"> Salir </a>

        </div>        

        <div class="guia_salida">
            <div class="form-group col-md-12 no_pl">  
              <div class="input-group">
                <span class="input-group-addon">Nro Oper</span>
                  <input class="form-control input-sm" data-de="nro_operacion" readonly="readonly" value="">     

              </div>
            </div>

            <div class="form-group col-md-12 no_pl">  
              <div class="input-group">
                <span class="input-group-addon">Fecha</span>
                  <input class="form-control input-sm" data-de="fecha" readonly="readonly" value="">     
              </div>
            </div>

            <div class="form-group col-md-12 no_pl">  
              <div class="input-group">
                <span class="input-group-addon">Almacen</span>
                  <select class="form-control input-sm" name="almacen_id" type="text" readonly="readonly" value="">     
                    @foreach( $almacenes as $almacen )
                      <option value="{{ $almacen->LocCodi }}">{{ $almacen->LocNomb }}</option>
                    @endforeach
                  </select>  
              </div>
            </div>

            <div class="form-group col-md-12 no_pl">  
              <div class="input-group">
                <span class="input-group-addon">Tipo mov:</span>
                  <select class="form-control input-sm" name="tipo_movimiento" type="text" readonly="readonly" value="">     
                    @foreach( $tipo_movimientos as $tipo_movimiento )
                    <option value="{{ $tipo_movimiento->Tmocodi }}">{{ $tipo_movimiento->TmoNomb }} </option>
                    @endforeach
                  </select>
              </div>
            </div>

            <div class="form-group col-md-12 no_pl">  
              <div class="input-group">
                <span class="input-group-addon">Nro Doc</span>
                  <div class="row">   
                    <div class="col-md-4 no_pr">                 
                      <input class="form-control input-sm" data-de="nro_seri" readonly="readonly">     
                    </div>
                    <div class="col-md-8 no_pl">                                     
                      <input class="form-control input-sm" data-de="nro_docu" readonly="readonly">     
                    </div>
                  </div>

              </div>
            </div>

        </div>
      </div>

    </div>


    </div>
    <!-- /.modal-content -->
  </div>


