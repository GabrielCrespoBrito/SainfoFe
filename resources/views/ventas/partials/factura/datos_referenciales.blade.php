<?php
$hide = true;
$is_nota_credito = null;
if (!$create) {
  $is_nota_credito = $venta->isNotaCredito();
}
if (!$create && $is_nota_credito) {
  $hide = false;
}
?>

<div class="row row_dat">
  <div class="div_datos_referenciales block {{ $hide ? 'hide' : '' }}">
    <p class="titulo_seccion title_seccion_datos_referencia">
      <span class="text"> Datos referenciales </span>
      <span data-type="0" data-toggle="tooltip" title="Opciòn si el documento al que quiere hacer su Nota de Credito o Debito se encuentra en el sistema" class="btn-tipo-nota-0  btn-tipo-nota btn btn-xs btn-flat btn-default">
        <span class="fa fa-desktop"> </span>
        Sistema </span>



      <span data-type="1" data-toggle="tooltip" title="Opciòn si el documento al que quiere hacer su Nota de Credito o Debito no se encuentra registrado en el sistema" class="btn-tipo-nota-1 btn-tipo-nota btn btn-xs btn-flat btn-default">
        <span class="fa fa-pencil"> </span>
        Manualmente </span>
    </p>
    <div class="form-group col-md-2 no_pr">
      <select disabled class="form-control input-sm" name="ref_documento">
        <option></option>
        <option {{ !$create ? $venta->VtaTDR == "01" ? "selected=selected"  : '' : '' }} value="01"> FACTURA c </option>
        <option {{ !$create ? $venta->VtaTDR == "03" ? "selected=selected"  : '' : '' }} value="03"> BOLETA </option>
      </select>
    </div>

    <div class="form-group col-md-2 no_pl">
      <div class="input-group">
        <span class="input-group-addon">Serie</span>
        <input disabled class="form-control input-sm" value="{{ !$create ? $venta->isNotaCredito() ? $venta->VtaSeriR : '' : '' }}" name="ref_serie">
      </div>
    </div>

    <div class="form-group col-md-2 no_pl">
      <div class="input-group">
        <span class="input-group-addon">Número</span>
        <input class="form-control input-sm group_ref" value="{{ !$create ? $venta->isNotaCredito() ? $venta->VtaNumeR : '' : '' }}" name="ref_numero" disabled>
      </div>
    </div>

    <div class="form-group col-md-2 no_pl">
      <div class="input-group">
        <span class="input-group-addon">Fecha</span>
        <input class="form-control input-sm group_ref datepicker" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ date('Y-m-d') }}" disabled value="{{ !$create ? $venta->isNotaCredito() ? $venta->VtaFVtaR : '' : date('Y-m-d') }}" name="ref_fecha">
      </div>
    </div>

    <!-- <input name="fecha_emision" data-date-format="yyyy-mm-dd" data-fecha_inicial="{{ date('Y-m-d') }}" required="required" class="form-control input-sm datepicker" value="2021-03-12" type="text"> -->


    <div class="form-group col-md-4 no_pl">
      <div class="input-group">
        <span class="input-group-addon">Tipo</span>
        @if($create)
        <select class="form-control input-sm" value="" name="ref_tipo">
          @foreach( $tipos_notacredito as $tipo_notacredito )
          <option {{ $loop->first ? 'selected=selected' : '' }} value="{{ $tipo_notacredito->id }}"> ({{ $tipo_notacredito->id }}) {{ $tipo_notacredito->descripcion }} </option>
          @endforeach
        </select>
        @else
        <input readonly="readonly" class="form-control input-sm group_ref" value="{{ $venta->tipo_nota ?  $venta->tipo_nota->id : '' }} " name="ref_motivo" disabled="disabled">
        @endif
      </div>
    </div>

  </div>
</div>