@php
$titulo = $titulo ?? 'Generar Guia';
$showForm = $showForm ?? false;
$showFecha = $showFecha ?? false;
$showCancelBtn = $showCancelBtn ?? true;

$id = $id ?? 'modalGuiaSalida';
$url = $url ?? '';
$guia_id = $guia_id ?? null;
$isCompra = $isCompra ?? false;
$local_disabled = $local_disabled ?? null; 

// dd( $showButtons );
$showButtons = $showButtons ?? true;
$tipoVenta = $tipoVenta ?? false;
$tm = new App\TipoMovimiento();

// dd( $local_disabled );


$isNC = $isCompra ? $compra->isNC() : null;
$code = $isNC ? App\TipoMovimiento::NC_COMPRA : App\TipoMovimiento::DEFAULT_INGRESO;
$tipo_movimientos = $tipo_movimientos ?? $tm->repository()->where('Tmocodi',$code);


$code = $tipoVenta ? App\TipoMovimiento::NC_VENTA : App\TipoMovimiento::NC_COMPRA;
$tipo_movimentos_nc = $tm->repository()->where('Tmocodi', $code);

$locales = $locales ?? auth()->user()->locales;
// dd($locales->first()->local);
@endphp

<div class="modal modal-seleccion fade" id="{{ $id }}" data-url="{{ $url }}">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ $titulo }} <span class="aditional-text"></span> </h4>
      </div>
      <div class="modal-body" style="overflow: hidden;">

        @if($showButtons)
        <div class="botones_div" style="margin-bottom:10px">
          <a class="btn pull-left btn-success btn-flat aceptar_guia"> <span class="fa fa-check"> </span> Aceptar</a>
          @if($showCancelBtn)
          <a class="btn pull-left btn-danger btn-flat salir_guia" data-dismiss="modal" aria-label="Close"> Salir </a>
          @endif
        </div>
        @endif

        <div class="guia_salida">
          @if($showForm)
            <form id="" data-route="{{ route('guia.traslado' , $guia_id)}}"  action="{{ route('guia.traslado' , $guia_id)}}" method="post">

              @csrf
          @endif
          @if($showFecha)
          <div class="form-group col-md-12 no_pl">
            <div class="input-group">
              <span class="input-group-addon">Fecha</span>
              <input name="fecha_emision" class="form-control input-sm datepicker" data-format="yyyy-mm-dd" data-de="fecha" value="{{ date('Y-m-d') }}">
            </div>
          @endif

          @php
          $local_user = auth()->user()->local();
          @endphp
          <div class="form-group col-md-12 no_pl mb-0">
            <div class="input-group">
              <span class="input-group-addon">Almacen</span>
              <select class="form-control input-sm" name="almacen_id" type="text">
                @foreach( $locales as $almacen )
                @php
                  $almacen = $almacen->local;
                  if(!$almacen->elegible()) continue;
                  if($local_disabled && $local_disabled == $almacen->LocCodi) continue;
                @endphp
                <option value="{{ $almacen->LocCodi }}" {{ $almacen->LocCodi == $local_user ? 'selected=selected' : '' }}>{{ $almacen->LocNomb }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group col-md-12 no_pl no_pr">
            <div class="input-group">
              <span class="input-group-addon">Tipo mov.:</span>
              <select class="form-control input-sm" data-regular={!! $tipo_movimientos !!} data-nc="{{ $tipo_movimentos_nc }}" name="tipo_movimiento">
                @foreach( $tipo_movimientos as $tipo_movimiento )
                <option value="{{ $tipo_movimiento->Tmocodi }}" {{ $tipo_movimiento->default() ? 'selected=selected' : '' }}>{{ $tipo_movimiento->TmoNomb }} </option>
                @endforeach
              </select>
            </div>
          </div>

          @if( $isCompra )
          <div class="form-group col-md-12 no_pl">
            <div class="row">
              <label class="col-md-3 control-label" style="margin-top:8px; padding-right: 0; font-weight: normal"> Nro Doc </label>
              <div class="col-md-4" style="padding:0">
                <input class="col-md-4 form-control input-sm" maxlength="4" required placeholder="Serie" name="serie">
              </div>
              <div class="col-md-5" style="padding:0">
                <input class="col-md-6 form-control input-sm" maxlength="8" required placeholder="Número" name="numero">
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>

    </div>

    @if($showForm)
      </form>
    @endif

  </div>
  <!-- /.modal-content -->
</div>