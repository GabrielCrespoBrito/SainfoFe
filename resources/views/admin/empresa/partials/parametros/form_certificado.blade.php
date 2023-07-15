<form id="form-cert" action="{{route('empresa.cert.store', $empresa->empcodi  ) }}" method="post" enctype="multipart/form-data">
<div class="row">

  <div class="col-md-6">
    <div class="title"> Información </div>
      @include('empresa.partials.form.cert_info')
  </div>

  <div class="col-md-6">
    <div class="title"> Archivos del certificado </div>
      @include('empresa.partials.form.cert_inputs')
  </div>

  <div class="col-md-12">
    <button type="submit" class="btn btn-primary btn-flat"> <span class="fa fa-save"> </span> Guardar </button>
  </div>

</div>

</form>