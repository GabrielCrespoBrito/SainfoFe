<div class="div-option" id="div-tc">
<div class="row " id="div-tc">

  <div class="form-group col-md-6">
    <div class="input-group">
      <span class="input-group-addon">T.C Lista</span>
      <input class="form-control" name="tipo_cambio" data-default="{{ $tc_venta }}" readonly="readonly" value="{{ $tc_venta }}">

      <span class="input-group-addon">

        <a href="#" data-toggle="tooltip" title="Modifica" class="btn action-default edit-tc btn-xs btn-default"> <span class="fa fa-pencil"></span> </a>

        <a href="#" style="display:none" data-toggle="tooltip" title="El tipo de cambio nuevo, se utilizara para recalcular los productos en dolares" data-url="{{ route('unidad.update_tc') }}" class="btn action-edit update-tc btn-xs btn-primary"> <span class="fa fa-save"></span> </a>

        <a href="#" style="display:none" data-toggle="tooltip" title="Cancelar la modificaciòn" class="btn action-edit cancel-edit-tc btn-xs btn-default"> <span class="fa fa-close"></span> </a>

      </span>
    </div>
  </div>

  <div class="form-group col-md-6">
    <div class="input-group">
      <span class="input-group-addon">T.C Sunat</span>
      <input class="form-control" name="tc_sunat" readonly="readonly" value="{{ $tc_sunat }}">
      <span data-toggle="tooltip" title="Fecha de la ultima actualizaciòn del tipo de cambio de la sunat {{$tc_sunat_fecha}}" style="font-style:italic" class="input-group-addon">{{ $tc_sunat_fecha  }}</span>
    </div>
  </div>

</div>
</div>