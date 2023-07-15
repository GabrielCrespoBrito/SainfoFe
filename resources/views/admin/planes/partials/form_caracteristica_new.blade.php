<form action="{{ route('admin.plan-caracteristica.create', $plan->id ) }}" method="post" id="form-caracteristica">
  @csrf  

<div class="parent-caracteristica">
  <div class="row mt-x10">    
    <div class="col-md-12">
      <label> Nombre </label>
      <input name="nombre" required="required" class="form-control" value="" />
    </div>
  </div>

  <div class="row mt-x10">  
    <div class="col-md-12">
      <input placeholder="Text Adicional" name="adicional" class="form-control" value="" />
    </div>
  </div>

  <div class="row mt-x10">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"></span> Guardar </button>
    </div>
  </div>
</div>

</form>