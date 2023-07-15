@php
  $area_admin = $area_admin ?? false;

  $route =  $area_admin ?
    route('admin.empresa.update_certs', $empresa->empcodi) :
    route('empresa.cert.store', $empresa->empcodi);
@endphp

<div class="row">
  <div class="{{ $area_admin ? 'col-md-8' : 'col-md-12' }}">
    <form id="form-cert" action="{{ $route }}" method="post" enctype="multipart/form-data">
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
  </div>

  @if($area_admin)
  <div class="col-md-4">
    <div class="title"> Archivos del certificado </div>
      @include('empresa.partials.form.cert_verificar')
  </div>
  @endif


</div>



