<form id="form-usuario" onsubmit="return false;" >

  <div class="row">

    <div class="form-group col-md-2">
        <label> ID </label>
        <input name="codigo" required="required" class="form-control" value="" type="text">
    </div>

    <div class="form-group col-md-4">
        <label> RUC </label>
        <input name="ruc" required="required" class="form-control" value="" type="text">
    </div>

    <div class="form-group col-md-6">
        <label> Razón social </label>
        <input name="razon_social" required="required" class="form-control" value="" type="text">
    </div>

  </div>

  <div class="row">

    <div class="form-group col-md-12">
      <label> Nombre Comercial: * </label>
      <input name="usuario"  required="required" class="form-control" type="text">
    </div>

  </div>

  <div class="row">

    <div class="form-group col-md-6">
        <label> Teléfonos </label>
        <input name="telefono" class="form-control" type="text">
    </div>

    <div class="form-group col-md-8">
        <label> Dirección </label>
        <input name="direccion" class="form-control" type="text">
    </div>
  
  </div>

  <div class="row">

    <div class="form-group col-md-6">
        <label> Email </label>
        <input name="email" required="required" class="form-control" type="text">
    </div>

    <div class="form-group col-md-8">
        <label> Rubro </label>
        <input name="rubro" required="required" class="form-control" type="text">
    </div>
  
  </div>

  <hr>
  
  <div class="row">

    <div class="col-xs-12">
        <a class="btn btn-primary btn-flat send_user_info"> <span class="fa fa-save"></span> Guardar</a>
    </div>

  </div>

</form>