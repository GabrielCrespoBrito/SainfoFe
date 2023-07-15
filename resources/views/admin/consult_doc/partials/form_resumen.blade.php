<form method="post" action="{{ route('admin.consultar_doc.resumen') }}">
  @csrf

  <div class="row">

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon">Ruc Empresa </span>
        <input class="form-control input-sm" required="required" name="ruc_empresa" type="text" value="{{ optional($empresa)->EmpLin1 }}">
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon"> Usuario Sol </span>
        <input class="form-control input-sm" required="required" name="usuario_sol" type="text" value="{{ optional($empresa)->FE_USUNAT }}">
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon">Clave Sol </span>
        <input class="form-control input-sm" required="required" name="clave_sol" type="text" value="{{ optional($empresa)->FE_UCLAVE }}">
      </div>
    </div>

  </div>

  <div class="row">

    <div class="form-group col-md-12">
      <div class="input-group">
        <span class="input-group-addon"> Ticket </span>
        <input class="form-control input-sm" required="required" name="ticket" type="text" value="">
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-md-12">
      <button class="btn btn-primary btn-flat" type="submit"><span class="fa fa-search"></span> Buscar </button>
      <button class="btn btn-default btn-flat" type="reset"><span class="fa fa-eraser"></span> Limpiar </button>
    </div>
  </div>

</form>
