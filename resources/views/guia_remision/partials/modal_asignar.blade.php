<div class="modal modal-seleccion fade" id="modalAsignarGuia">
  <div class="modal-dialog modal-sm">
  <div class="modal-content">
  <div class="modal-header">
  <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
  <!-- <span aria-hidden="true">&times;</span></button> -->
  <h4 class="modal-title">Generar guia de salida</h4>
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
  <input class="form-control input-sm" data-de="fecha" value="{{ date('Y-m-d') }}" readonly="readonly" value="">     




  
  </div>
  </div>

  <div class="form-group col-md-12 no_pl">  
  <div class="input-group">
  <span class="input-group-addon">Almacen</span>
  <select class="form-control input-sm" name="almacen_id" type="text" readonly="readonly" value="">     
  @php 
    $empresa = get_empresa();
    $almacenes = $empresa->almacenes;
    $tipo_movimientos  = App\TipoMovimiento::activos();

  @endphp

  @foreach( $almacenes as $almacen )
    @if($almacen->elegible())
    <option value="{{ $almacen->LocCodi }}" {{ $almacen->default() ? 'selected=selected' : ''  }} >{{ $almacen->LocNomb }}</option>
    @endif
  @endforeach

  </select>  
  </div>
  </div>

  <div class="form-group col-md-12 no_pl">  
  <div class="input-group">
  <span class="input-group-addon">Tipo mov:</span>
  <select class="form-control input-sm" name="tipo_movimiento" readonly="readonly">     
  @foreach( $tipo_movimientos as $tipo_movimiento )
    <option value="{{ $tipo_movimiento->Tmocodi }}" {{ $tipo_movimiento->default() ? '  selected=selected' : '' }}>{{ $tipo_movimiento->TmoNomb }} </option>
  @endforeach
  </select>
  </div>
  </div>

  </div>
  </div>

  </div>


  </div>
  <!-- /.modal-content -->
  </div>


