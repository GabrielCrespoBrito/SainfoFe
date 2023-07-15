<form>

  <!-- row -->
  <div class="row">
    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Moneda</span>
        <select class="form-control input-sm" data-field="MonCodi" name="moneda" type="text">
          <option value="01">SOLES</option>
          <option value="02">DOLAR</option>
        </select>
      </div>
    </div>

    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Fecha</span>
        <input class="form-control datepicker input-sm" data-field="MocFech" data-default="{{ date('Y-m-d') }}" name="fecha" value="" type="text">
      </div>
    </div>
  </div>
  <!-- /row -->

  <!-- row -->
  <div class="row">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Opción Egreso</span>
        <select data-field="CtoCodi" name="egreso_tipo" required="required" class="form-control">
          <option data-default="selected" value="005">Gasto</option>
          <option value="006">Retiro</option>
          <option value="015">Transferencia Caja</option>
          <option value="020">Personal</option>
          <option value="09">Transferencia Banco</option>
        </select>
      </div>
    </div>
  </div>
  <!-- /row -->

  <!-- row -->
  <div class="row transferencia_banco" data-id="09" style="display: none">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Transferencia Banco</span>
        <select data-field="CtaImpo" name="banco_id" required="required" class="form-control">
          @foreach( $cuentas as $cuenta )
          <option value="{{ $cuenta->CueCodi }}"> ({{ $cuenta->banco->bannomb }}) - {{ $cuenta->CueNume }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <!-- /row -->

  <!-- row -->
  <div class="row transferencia_caja" data-id="015" style="display: none">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Transferencia de caja</span>
        <select data-field="CajNume" name="caja_transferencia" required="required" class="form-control">
          @foreach( $cajas as $caja )
          <option value="{{ $caja->CajNume }}">({{ $caja->User_Crea }}) {{ $caja->CajNume }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <!-- /row -->


  <!-- row -->
  <div class="row personales_select" data-id="020" style="display: none">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Personal</span>
        <select data-field="PCCodi" name="personal_id" required="required" class="form-control">
          @foreach( $personales as $personal )
          <option value="{{ $personal->RHPCodi }}">{{ $personal->RHPNomb }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <!-- /row -->

  @php
  $transportistaNombre = "";
  $transportistaId = "";
  @endphp

  <!-- row motivo -->
  <div class="row">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Motivo</span>
        <select placeholder="Elegir Motivo" data-settings="{{ json_encode(['minimuminputlength']) }}" data-minimuminputlength="0" class="form-control input-sm select2" data-url="{{ route('cajas.motivos_search' , 'S') }}" data-text="{{ $transportistaNombre }}" data-id="{{ $transportistaId }}" data-field="EgrIng" name="motivo" style="display:none;position:absolute">
        </select>
        <div class="input-group-addon">
          <a target="_blank" href="{{ route('cajas.motivos_show' , 'egresos') }}" class=""> <i class="fa fa-plus"></i> </a>
        </div>
      </div>
    </div>
  </div>
  <!-- /row motivo -->





  <input type="hidden" data-field="Id" name="Id">

  <!-- /row -->
  <div class="row">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Nombre</span>
        <input class="form-control input-sm " name="nombre" data-field="MocNomb" data-field_edit="MocNume" value="" type="text">

      </div>
    </div>
  </div>
  <!-- /row -->

  <!-- row -->
  <div class="row">

    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Monto</span>
        <input class="form-control input-sm" name="monto" data-default="0.00" data-field="CANEGRS" value="0.00" type="number">
      </div>
    </div>

    <div class="form-group col-md-6">
      <div class="input-group">
        <span class="input-group-addon">Otro Doc.:</span>
        <input class="form-control input-sm" name="otro_doc" data-field="OTRODOC" value="" type="text">
      </div>
    </div>

  </div>
  <!-- /row -->

  <!-- row -->
  <div class="row">
    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon">Autoriza:</span>
        <input class="form-control input-sm" data-field="AUTORIZA" data-default="{{ auth()->user()->usunomb }}" name="autoriza" value="{{ auth()->user()->usunomb }}" type="text">
      </div>
    </div>
  </div>
  <!-- /row -->

</form>
