<div class="row">
  <div class="col-md-6 pull-right noborders">
    <div class="row">
      <div class="col-md-12">
        <div class="input-group pull-right">
          <div class="input-group-addon">Aperturada</div>
          <div class="input-group-addon">S./
            <strong class="money_sol">{{ optional($caja->apertura())->ingreso_soles() }}
            </strong>
          </div>
          <div class="input-group-addon">USD
            <strong class="money_dolar"> {{ optional($caja->apertura())->ingreso_dolar() }}
            </strong>
          </div>
          <div class="input-group-addon"><a href="#" class="btn btn-xs show-modalapertura"> <span class="fa fa-pencil"></span> </a></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 pull-left noborders">
    <div class="row">
      <div class="col-md-12">
        <div class="input-group pull-right">

          <div class="input-group-addon">Totales</div>

          <div class="input-group-addon">S./
            <strong class="">{{ $total_soles }} </strong>
          </div>

          <div class="input-group-addon">USD
            <strong class=""> {{ $total_dolar }} </strong>
          </div>

          <div class="input-group-addon"><a href="#" class="btn btn-xs show-modalapertura"> &nbsp; </a></div>

        </div>
      </div>
    </div>
  </div>


</div>


<div class="row">

  <div class="col-md-6 text-right ">
    <div class="row">
      <div class="col-md-6 ventas text-left">
        <select name="tipos_movimientos" class="form-control  text-center">
          <option {{ $tipo_movimiento == "ingresos" ? 'selected=selected' : '' }} value="{{route('cajas.movimientos', [ 'id_caja' => $caja->CajNume , 'tipo_movimiento' => 'ingresos']  )  }}"> Ingresos </option>
          <option {{ $tipo_movimiento == "egresos" ? 'selected=selected' : '' }} value="{{route('cajas.movimientos', [ 'id_caja' => $caja->CajNume , 'tipo_movimiento' => 'egresos']  )  }}"> Egresos </option>
        </select>
      </div>
      <div class="col-md-6">
        <a href="#" class="btn btn-default {{ $is_ingreso ? 'show-modalingreso' : 'show-modalegreso'  }} pull-left btn-flat"> <span class="fa fa-plus"></span> Nuevo </a>
      </div>
    </div>
  </div>

  <div class="col-md-6 text-right ">

    <a href="{{ route('cajas.resumen_pdf_detallado', $caja->CajNume) }}" target="blank" class="btn btn-default btn-flat"> <span class="fa"></span> Reporte </a>

  </div>


</div>