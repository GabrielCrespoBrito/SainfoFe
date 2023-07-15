<form class="mt-x10" method="post" action="{{ route('admin.consultar_doc.documento') }}">
  @csrf

  <div class="row">

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon">Ruc Empresa </span>
        <input class="target-resaltar form-control input-sm" required="required" name="ruc_empresa" type="text" value="{{ optional($empresa)->EmpLin1 }}">
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon"> Usuario Sol </span>
        <input class="target-resaltar form-control input-sm" required="required" name="usuario_sol" type="text" value="{{ optional($empresa)->FE_USUNAT }}">
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon">Clave Sol </span>
        <input class="target-resaltar form-control input-sm" required="required" name="clave_sol" type="text" value="{{ optional($empresa)->FE_UCLAVE }}">
      </div>
    </div>

  </div>

  <div class="row">

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon">Tipo Documento </span>
        <select class="form-control input-sm" required="required" name="tipo_documento">
          <option value="01"> Factura </option>
          <option value="03"> Boleta </option>
          <option value="07"> Nota de Credito </option>
          <option value="08"> Nota de Debito </option>
        </select>
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon"> Serie </span>
        <input class="form-control input-sm" required="required" name="serie_documento" type="text" value="F001">
      </div>
    </div>

    <div class="form-group col-md-4">
      <div class="input-group">
        <span class="input-group-addon"> Numero </span>
        <input class="form-control input-sm" required="required" name="numero" type="text" value="000001">
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-md-6">
      <button class="btn btn-primary btn-flat" type="submit"><span class="fa fa-search"></span> Buscar </button>
      <button class="btn btn-default btn-flat" type="reset"><span class="fa fa-eraser"></span> Limpiar </button>
    </div>

    <div class="col-md-6">
      <div class="pull-right">
      <label for="cdr" class="label-radio-style">
      <input id="cdr" type="radio" checked name="tipo" value="cdr"> CDR 
      </label>
      <label for="estado" class="label-radio-style" style="margin-left:20px;">
      <input id="estado" type="radio" name="tipo" value="estado"> Estado
      </label>
      </div>
    </div>
  </div>
</form>