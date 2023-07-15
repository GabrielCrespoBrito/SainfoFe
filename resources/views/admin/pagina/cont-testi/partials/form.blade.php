@php
  $create = ! $testimonio->exists;
  $route = $create ? route('admin.pagina.contabilidad-testi.store') : route('admin.pagina.contabilidad-testi.update', $testimonio->id) ; 
@endphp

@include('partials.errors_html')

<form enctype="multipart/form-data" method="post" action="{{ $route }}" >
  @csrf
  <div class="row">

    <div class="form-group col-md-6">
      <label htmlFor="representante">Representante</label>
      <input
        required
        type="text"
        name="representante"
        id="representante"
        class="form-control"
        value="{{ old('representante',$testimonio->representante) }}"
        placeholder="Representante" />
    </div>

    <div class="form-group col-md-6">
      <label htmlFor="cargo">Cargo</label>
      <input
        required
        type="text"
        id="cargo"
        name="cargo"
        class="form-control"
        value="{{ old('cargo',$testimonio->cargo) }}"
        placeholder="Cargo de Representante" />
    </div>

  </div>

  <div class="row">

    <div class="form-group col-md-12">
      <label htmlFor="testimonio"> Testimonio </label>
      <textarea
        id="testimonio"
        name="testimonio_text"
        type="text"
        max="500"
        class="form-control"
        rows="3">{{ old('testimonio_text',$testimonio->testimonio_text) }}</textarea>
    </div>

  </div>


  <div class="row">

  <div class="col-md-6 form-group">
    <label>Imagen</label>

    <input
      name="imagen"
      {{ $create ? 'required' : '' }}
      type="file"
      class="form-control"
    />
  </div>

  <div class="col-md-6 form-group">
    <label></label>
    <img style="width:100px" src="{{ old('imagen', $testimonio->pathImage()) }}" alt="" class="rounded-circle img-fluid">
  </div>

  </div>

  <div class="row">
    <div class="col-md-12">
      
      <button class="btn btn-flat btn-primary" type="submit"> 
        Guardar 
      </button>
      
      <a 
        href="{{ route('admin.pagina.contabilidad-testi.index') }}"
        class="btn btn-flat btn-danger pull-right"> Cancelar 
      </a>

    </div>
  </div>
</form>